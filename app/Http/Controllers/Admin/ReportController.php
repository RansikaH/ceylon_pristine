<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesReportExport;

class ReportController extends Controller
{
    public function salesReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        $query = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled');
            
        $orders = $query->orderBy('created_at', 'desc')->get();
        
        $totalRevenue = $query->sum('total');
        $totalOrders = $query->count();
        $avgOrderValue = $query->avg('total');
        
        // Daily sales data for chart
        $dailySales = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Status breakdown
        $statusBreakdown = Order::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count, SUM(total) as total')
            ->groupBy('status')
            ->get();
        
        return view('admin.reports.sales', compact(
            'orders', 
            'totalRevenue', 
            'totalOrders', 
            'avgOrderValue',
            'dailySales',
            'statusBreakdown',
            'startDate',
            'endDate'
        ));
    }
    
    public function itemWiseSalesReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        // Get completed orders with items within date range
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->whereNotNull('items')
            ->get();
            
        $itemSales = [];
        $totalRevenue = 0;
        $totalQuantity = 0;
        
        foreach ($orders as $order) {
            $orderItems = $order->items;
            
            // Handle both array and string JSON
            if (is_string($orderItems)) {
                $orderItems = json_decode($orderItems, true);
            }
            
            if (is_array($orderItems)) {
                foreach ($orderItems as $item) {
                    $productId = $item['product_id'] ?? null;
                    if ($productId) {
                        if (!isset($itemSales[$productId])) {
                            $itemSales[$productId] = [
                                'product_id' => $productId,
                                'quantity' => 0,
                                'revenue' => 0,
                                'orders' => 0
                            ];
                        }
                        
                        $quantity = $item['quantity'] ?? 1;
                        $price = $item['price'] ?? 0;
                        
                        $itemSales[$productId]['quantity'] += $quantity;
                        $itemSales[$productId]['revenue'] += $quantity * $price;
                        $itemSales[$productId]['orders'] += 1;
                        
                        $totalRevenue += $quantity * $price;
                        $totalQuantity += $quantity;
                    }
                }
            }
        }
        
        // Get product details
        $productIds = array_keys($itemSales);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        
        // Merge product details with sales data
        foreach ($itemSales as $productId => &$data) {
            if (isset($products[$productId])) {
                $data['product_name'] = $products[$productId]->name;
                $data['category'] = $products[$productId]->category->name ?? 'N/A';
                $data['sku'] = $products[$productId]->sku ?? 'N/A';
                $data['current_stock'] = $products[$productId]->stock;
            } else {
                $data['product_name'] = 'Unknown Product';
                $data['category'] = 'N/A';
                $data['sku'] = 'N/A';
                $data['current_stock'] = 0;
            }
        }
        
        // Sort by revenue descending
        uasort($itemSales, function($a, $b) {
            return $b['revenue'] <=> $a['revenue'];
        });
        
        return view('admin.reports.item-wise-sales', compact(
            'itemSales',
            'totalRevenue',
            'totalQuantity',
            'startDate',
            'endDate'
        ));
    }
    
    public function districtWiseSalesReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        // Get orders with user addresses within date range
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->with('user')
            ->get();
            
        $districtSales = [];
        $totalRevenue = 0;
        $totalOrders = 0;
        
        foreach ($orders as $order) {
            $district = 'Unknown';
            
            // Try to get district from shipping address first
            if (!empty($order->shipping_address)) {
                $address = $order->shipping_address;
                if (is_string($address)) {
                    $address = json_decode($address, true);
                }
                $district = $address['district'] ?? 'Unknown';
            }
            
            // If no district in shipping address, get from customer's district field
            if ($district === 'Unknown' && $order->user) {
                $district = $order->user->district ?? 'Unknown';
            }
            
            // Fallback: try to parse from user's address lines if district field is empty
            if ($district === 'Unknown' && $order->user) {
                $fullAddress = trim(($order->user->address_line_1 ?? '') . ' ' . ($order->user->address_line_2 ?? ''));
                if (!empty($fullAddress)) {
                    // Extract district from address text (common Sri Lankan districts)
                    $sriLankanDistricts = [
                        'Colombo', 'Gampaha', 'Kalutara', 'Kandy', 'Matale', 'Nuwara Eliya',
                        'Galle', 'Matara', 'Hambantota', 'Jaffna', 'Kilinochchi', 'Mannar',
                        'Vavuniya', 'Mullaitivu', 'Batticaloa', 'Ampara', 'Trincomalee',
                        'Kurunegala', 'Puttalam', 'Anuradhapura', 'Polonnaruwa', 'Badulla',
                        'Monaragala', 'Ratnapura', 'Kegalle'
                    ];
                    
                    foreach ($sriLankanDistricts as $districtName) {
                        if (stripos($fullAddress, $districtName) !== false) {
                            $district = $districtName;
                            break;
                        }
                    }
                }
            }
            
            if (!isset($districtSales[$district])) {
                $districtSales[$district] = [
                    'district' => $district,
                    'revenue' => 0,
                    'orders' => 0,
                    'customers' => []
                ];
            }
            
            $districtSales[$district]['revenue'] += $order->total;
            $districtSales[$district]['orders'] += 1;
            $districtSales[$district]['customers'][] = $order->user_id;
            
            $totalRevenue += $order->total;
            $totalOrders += 1;
        }
        
        // Count unique customers per district
        foreach ($districtSales as &$data) {
            $data['unique_customers'] = count(array_unique($data['customers']));
            unset($data['customers']);
        }
        
        // Sort by revenue descending
        uasort($districtSales, function($a, $b) {
            return $b['revenue'] <=> $a['revenue'];
        });
        
        // Convert to indexed array for JavaScript
        $districtSales = array_values($districtSales);
        
        return view('admin.reports.district-wise-sales', compact(
            'districtSales',
            'totalRevenue',
            'totalOrders',
            'startDate',
            'endDate'
        ));
    }
    
    public function monthlyItemDemandReport(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $months = [];
        
        // Generate all months for the selected year
        for ($month = 1; $month <= 12; $month++) {
            $months[] = Carbon::createFromDate($year, $month, 1)->format('Y-m');
        }
        
        $monthlyDemand = [];
        
        foreach ($months as $month) {
            $monthStart = Carbon::parse($month)->startOfMonth();
            $monthEnd = Carbon::parse($month)->endOfMonth();
            
            // Get orders with items for this month (include all statuses except cancelled)
            $orders = Order::whereBetween('created_at', [$monthStart, $monthEnd])
                ->where('status', '!=', 'cancelled')
                ->whereNotNull('items')
                ->get();
                
            $itemDemand = [];
            
            foreach ($orders as $order) {
                $orderItems = $order->items;
                
                // Handle both array and string JSON
                if (is_string($orderItems)) {
                    $orderItems = json_decode($orderItems, true);
                }
                
                if (is_array($orderItems)) {
                    foreach ($orderItems as $item) {
                        $productId = $item['product_id'] ?? null;
                        if ($productId) {
                            if (!isset($itemDemand[$productId])) {
                                $itemDemand[$productId] = [
                                    'product_id' => $productId,
                                    'quantity' => 0,
                                    'revenue' => 0
                                ];
                            }
                            
                            $quantity = floatval($item['quantity'] ?? 1);
                            $price = floatval($item['price'] ?? 0);
                            
                            $itemDemand[$productId]['quantity'] += $quantity;
                            $itemDemand[$productId]['revenue'] += $quantity * $price;
                        }
                    }
                }
            }
            
            // Get product details and sort by quantity
            if (!empty($itemDemand)) {
                $productIds = array_keys($itemDemand);
                $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
                
                foreach ($itemDemand as $productId => &$data) {
                    if (isset($products[$productId])) {
                        $data['product_name'] = $products[$productId]->name;
                        $data['category'] = $products[$productId]->category->name ?? 'N/A';
                    } else {
                        $data['product_name'] = 'Unknown Product';
                        $data['category'] = 'N/A';
                    }
                }
                
                // Sort by quantity descending and take top 20
                uasort($itemDemand, function($a, $b) {
                    return $b['quantity'] <=> $a['quantity'];
                });
                
                $monthlyDemand[$month] = array_values(array_slice($itemDemand, 0, 20, true));
            } else {
                $monthlyDemand[$month] = [];
            }
        }
        
        // Get overall top products for the year
        $yearStart = Carbon::createFromDate($year, 1, 1)->startOfYear();
        $yearEnd = Carbon::createFromDate($year, 12, 31)->endOfYear();
        
        $yearlyOrders = Order::whereBetween('created_at', [$yearStart, $yearEnd])
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('items')
            ->get();
            
        $yearlyDemand = [];
        
        foreach ($yearlyOrders as $order) {
            $orderItems = $order->items;
            
            // Handle both array and string JSON
            if (is_string($orderItems)) {
                $orderItems = json_decode($orderItems, true);
            }
            
            if (is_array($orderItems)) {
                foreach ($orderItems as $item) {
                    $productId = $item['product_id'] ?? null;
                    if ($productId) {
                        if (!isset($yearlyDemand[$productId])) {
                            $yearlyDemand[$productId] = [
                                'product_id' => $productId,
                                'quantity' => 0,
                                'revenue' => 0
                            ];
                        }
                        
                        $quantity = floatval($item['quantity'] ?? 1);
                        $price = floatval($item['price'] ?? 0);
                        
                        $yearlyDemand[$productId]['quantity'] += $quantity;
                        $yearlyDemand[$productId]['revenue'] += $quantity * $price;
                    }
                }
            }
        }
        
        // Get product details for yearly data
        if (!empty($yearlyDemand)) {
            $yearlyProductIds = array_keys($yearlyDemand);
            $yearlyProducts = Product::whereIn('id', $yearlyProductIds)->get()->keyBy('id');
            
            foreach ($yearlyDemand as $productId => &$data) {
                if (isset($yearlyProducts[$productId])) {
                    $data['product_name'] = $yearlyProducts[$productId]->name;
                    $data['category'] = $yearlyProducts[$productId]->category->name ?? 'N/A';
                } else {
                    $data['product_name'] = 'Unknown Product';
                    $data['category'] = 'N/A';
                }
            }
        }
        
        uasort($yearlyDemand, function($a, $b) {
            return $b['quantity'] <=> $a['quantity'];
        });
        
        // Convert to indexed array for JavaScript
        $yearlyDemand = array_values($yearlyDemand);
        
        return view('admin.reports.monthly-item-demand', compact(
            'monthlyDemand',
            'yearlyDemand',
            'year',
            'months'
        ));
    }
}

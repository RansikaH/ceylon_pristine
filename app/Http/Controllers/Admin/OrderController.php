<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $query = Order::with('user')->latest();

            // Apply filters
            if (request()->filled('status')) {
                $query->where('status', request('status'));
            }

            if (request()->filled('payment_method')) {
                $query->where('payment_method', request('payment_method'));
            }

            if (request()->filled('search')) {
                $search = request('search');
                $query->where(function($q) use ($search) {
                    $q->where('customer_name', 'like', "%{$search}%")
                      ->orWhere('customer_email', 'like', "%{$search}%")
                      ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            }

            if (request()->filled('date_from')) {
                $query->whereDate('created_at', '>=', request('date_from'));
            }

            if (request()->filled('date_to')) {
                $query->whereDate('created_at', '<=', request('date_to'));
            }

            if (request()->filled('amount_min')) {
                $query->where('total', '>=', request('amount_min'));
            }

            if (request()->filled('amount_max')) {
                $query->where('total', '<=', request('amount_max'));
            }

            $orders = $query->paginate(15);

            // Get order statistics
            $stats = [
                'completed_orders' => Order::where('status', 'completed')->count(),
                'processing_orders' => Order::where('status', 'processing')->count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
            ];

            return view('admin.orders.index', compact('orders', 'stats'));
        } catch (\Exception $e) {
            \Log::error('Error loading admin orders: ' . $e->getMessage());
            
            // Return empty orders collection with error message
            $orders = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
            $stats = [
                'completed_orders' => 0,
                'processing_orders' => 0,
                'pending_orders' => 0,
            ];
            return view('admin.orders.index', compact('orders', 'stats'));
        }
    }

    public function show(Order $order)
    {
        try {
            $order->load(['user', 'statusHistory.changedBy']);
            return view('admin.orders.show', compact('order'));
        } catch (\Exception $e) {
            \Log::error('Error loading order details: ' . $e->getMessage());
            return back()->with('error', 'Unable to load order details');
        }
    }

    public function updateStatus(Order $order, Request $request)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,processing,completed,cancelled',
                'note' => 'nullable|string|max:500'
            ]);

            $oldStatus = $order->status;
            
            // Only log if status actually changed
            if ($oldStatus !== $request->status) {
                // Create status history record
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'old_status' => $oldStatus,
                    'new_status' => $request->status,
                    'changed_by' => auth()->id(),
                    'notes' => $request->note
                ]);
            }

            $order->update([
                'status' => $request->status,
                'status_note' => $request->note
            ]);

            // Log order status change as activity
            ActivityLogger::orderStatusChanged($order, $oldStatus, $request->status);

            // Log the status change
            \Log::info('Order status updated', [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'note' => $request->note,
                'updated_by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully',
                'new_status' => $request->status
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating order status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating order status'
            ], 500);
        }
    }

    public function getStatus(Order $order)
    {
        try {
            return response()->json([
                'success' => true,
                'current_status' => $order->status,
                'status_note' => $order->status_note
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting order status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving order status'
            ], 500);
        }
    }

    public function getDetails(Order $order)
    {
        try {
            $order->load(['user']);
            $html = view('admin.orders.partials.order-details', compact('order'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting order details: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving order details'
            ], 500);
        }
    }

    public function updateQuantities(Order $order, Request $request)
    {
        try {
            $request->validate([
                'items' => 'required|array',
                'items.*.index' => 'required|integer',
                'items.*.quantity' => 'required|numeric|min:0.1'
            ]);

            // Get current order items
            $currentItems = $order->items ?? [];
            
            // Update quantities based on the request
            foreach ($request->items as $item) {
                $index = $item['index'];
                if (isset($currentItems[$index])) {
                    $currentItems[$index]['quantity'] = $item['quantity'];
                }
            }

            // Recalculate total
            $newTotal = 0;
            foreach ($currentItems as $item) {
                $newTotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
            }

            // Update the order
            $order->update([
                'items' => $currentItems,
                'total' => $newTotal
            ]);

            // Log the change
            \Log::info('Order quantities updated', [
                'order_id' => $order->id,
                'old_total' => $order->getOriginal('total'),
                'new_total' => $newTotal,
                'updated_by' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order quantities updated successfully',
                'new_total' => $newTotal
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating order quantities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating order quantities'
            ], 500);
        }
    }

    public function export(Request $request)
    {
        try {
            $request->validate([
                'format' => 'required|in:csv,excel,pdf',
                'date_range' => 'required|in:all,today,week,month,year,custom'
            ]);

            $query = Order::with('user')->latest();

            // Apply date range filter
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
                case 'custom':
                    if ($request->filled('export_date_from')) {
                        $query->whereDate('created_at', '>=', $request->export_date_from);
                    }
                    if ($request->filled('export_date_to')) {
                        $query->whereDate('created_at', '<=', $request->export_date_to);
                    }
                    break;
            }

            $orders = $query->get();

            switch ($request->format) {
                case 'csv':
                    return $this->exportCSV($orders, $request);
                case 'excel':
                    return $this->exportExcel($orders, $request);
                case 'pdf':
                    return $this->exportPDF($orders, $request);
            }
        } catch (\Exception $e) {
            \Log::error('Error exporting orders: ' . $e->getMessage());
            return back()->with('error', 'Error exporting orders');
        }
    }

    private function exportCSV($orders, $request)
    {
        $filename = "orders_" . date('Y-m-d_H-i-s') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($orders, $request) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper special character support
            fwrite($file, "\xEF\xBB\xBF");
            
            // CSV headers
            $headers = ['Order ID', 'Date', 'Customer Name', 'Customer Email', 'Customer Phone', 'Total', 'Payment Method', 'Status'];
            
            if ($request->include_address) {
                $headers[] = 'Shipping Address';
            }
            
            if ($request->include_items) {
                $headers[] = 'Items';
            }
            
            fputcsv($file, $headers);
            
            // Data rows
            foreach ($orders as $order) {
                $row = [
                    str_pad($order->id, 6, '0', STR_PAD_LEFT),
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->customer_name,
                    $order->customer_email,
                    $order->customer_phone,
                    $order->total,
                    $order->payment_method_display,
                    ucfirst($order->status)
                ];
                
                if ($request->include_address) {
                    $row[] = $order->full_address;
                }
                
                if ($request->include_items) {
                    $items = [];
                    if ($order->items) {
                        foreach ($order->items as $item) {
                            $items[] = ($item['name'] ?? 'Unknown') . ' x' . ($item['quantity'] ?? 1);
                        }
                    }
                    $row[] = implode(', ', $items);
                }
                
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    private function exportExcel($orders, $request)
    {
        // For now, return CSV (you can integrate Laravel Excel package later)
        return $this->exportCSV($orders, $request);
    }

    private function exportPDF($orders, $request)
    {
        // For now, redirect back with message (you can integrate PDF package later)
        return back()->with('info', 'PDF export will be available soon. Please use CSV format for now.');
    }

    public function print(Order $order)
    {
        try {
            $order->load(['user']);
            return view('admin.orders.print', compact('order'));
        } catch (\Exception $e) {
            \Log::error('Error generating print view: ' . $e->getMessage());
            return back()->with('error', 'Unable to generate print view');
        }
    }

    public function bulkUpdateStatus(Request $request)
    {
        try {
            $request->validate([
                'order_ids' => 'required|array',
                'order_ids.*' => 'exists:orders,id',
                'status' => 'required|in:pending,processing,completed,cancelled',
                'note' => 'nullable|string|max:500'
            ]);

            $updated = Order::whereIn('id', $request->order_ids)->update([
                'status' => $request->status,
                'status_note' => $request->note
            ]);

            // Log bulk status change as activity
            $orders = Order::whereIn('id', $request->order_ids)->get();
            foreach ($orders as $order) {
                ActivityLogger::log('order_bulk_status_changed', 
                    "Order #{$order->id} status updated to {$request->status} (bulk update)", 
                    $order, 
                    ['new_status' => $request->status, 'bulk_update' => true]
                );
            }

            return response()->json([
                'success' => true,
                'message' => "Updated status for {$updated} orders"
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in bulk status update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating order statuses'
            ], 500);
        }
    }
}

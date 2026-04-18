<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .order-info {
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h3 {
            margin-bottom: 10px;
            font-size: 16px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .total {
            font-weight: bold;
            font-size: 14px;
        }
        .status-badge {
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-processing { background-color: #cff4fc; color: #055160; }
        .status-completed { background-color: #d1e7dd; color: #0f5132; }
        .status-cancelled { background-color: #f8d7da; color: #842029; }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <p>Order Receipt</p>
    </div>

    <div class="section">
        <h3>Order Information</h3>
        <table>
            <tr>
                <td><strong>Order ID:</strong></td>
                <td>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td><strong>Date:</strong></td>
                <td>{{ $order->created_at->format('M d, Y \a\t h:i A') }}</td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td>
                    <span class="status-badge status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td><strong>Payment Method:</strong></td>
                <td>{{ $order->payment_method_display }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h3>Customer Information</h3>
        <table>
            <tr>
                <td><strong>Name:</strong></td>
                <td>{{ $order->customer_name }}</td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td>{{ $order->customer_email }}</td>
            </tr>
            <tr>
                <td><strong>Phone:</strong></td>
                <td>{{ $order->customer_phone }}</td>
            </tr>
            @if($order->full_address)
            <tr>
                <td><strong>Address:</strong></td>
                <td>{{ $order->full_address }}</td>
            </tr>
            @endif
        </table>
    </div>

    @if($order->items && count($order->items) > 0)
    <div class="section">
        <h3>Order Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item['name'] ?? 'Unknown Product' }}</td>
                    <td class="text-right">{{ number_format($item['quantity'] ?? 1, 1, '.', '') }}</td>
                    <td class="text-right">LKR {{ number_format($item['price'] ?? 0, 2) }}</td>
                    <td class="text-right">LKR {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="total text-right">Grand Total:</td>
                    <td class="total text-right">LKR {{ number_format($order->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif

    @if($order->status_note)
    <div class="section">
        <h3>Status Note</h3>
        <p>{{ $order->status_note }}</p>
    </div>
    @endif

    <div class="no-print" style="text-align: center; margin-top: 30px;">
        <button onclick="window.print()" class="btn btn-primary">Print Order</button>
    </div>
</body>
</html>

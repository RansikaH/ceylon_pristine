<div class="row">
    <div class="col-md-6">
        <h6 class="fw-bold mb-3">Order Information</h6>
        <table class="table table-sm">
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
                <td>@include('admin.orders.partials.status-badge', ['status' => $order->status])</td>
            </tr>
            <tr>
                <td><strong>Payment:</strong></td>
                <td>{{ $order->payment_method_display }}</td>
            </tr>
            <tr>
                <td><strong>Total:</strong></td>
                <td><strong class="text-primary">LKR {{ number_format($order->total, 2) }}</strong></td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h6 class="fw-bold mb-3">Customer Details</h6>
        <table class="table table-sm">
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
</div>

@if($order->items && count($order->items) > 0)
<div class="mt-4">
    <h6 class="fw-bold mb-3">Order Items</h6>
    <div class="table-responsive">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Discount</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $totalSavings = 0; @endphp
                @foreach($order->items as $item)
                @php
                    $hasDiscount = isset($item['discount_percentage']) && $item['discount_percentage'] > 0;
                    $unitPrice = $hasDiscount ? ($item['discounted_price'] ?? $item['price']) : $item['price'];
                    $itemTotal = $unitPrice * ($item['quantity'] ?? 1);
                    if ($hasDiscount) {
                        $savings = ($item['price'] - $unitPrice) * ($item['quantity'] ?? 1);
                        $totalSavings += $savings;
                    }
                @endphp
                <tr>
                    <td>
                        {{ $item['name'] ?? 'Unknown Product' }}
                        @if($hasDiscount)
                            <span class="badge bg-danger small ms-1">{{ number_format($item['discount_percentage'], 0) }}% OFF</span>
                        @endif
                    </td>
                    <td>{{ number_format($item['quantity'] ?? 1, 1, '.', '') }}</td>
                    <td>
                        @if($hasDiscount)
                            <div class="text-muted text-decoration-line-through small">
                                LKR {{ number_format($item['price'] ?? 0, 2) }}
                            </div>
                            <div class="text-danger fw-semibold">
                                LKR {{ number_format($unitPrice, 2) }}
                            </div>
                        @else
                            LKR {{ number_format($item['price'] ?? 0, 2) }}
                        @endif
                    </td>
                    <td>
                        @if($hasDiscount)
                            <span class="text-success small">
                                -LKR {{ number_format(($item['price'] - $unitPrice) * ($item['quantity'] ?? 1), 2) }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td><strong>LKR {{ number_format($itemTotal, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                @if($totalSavings > 0)
                <tr class="table-success">
                    <td colspan="4" class="text-end"><strong>Total Savings:</strong></td>
                    <td><strong class="text-success">LKR {{ number_format($totalSavings, 2) }}</strong></td>
                </tr>
                @endif
                <tr>
                    <td colspan="4" class="text-end"><strong>Grand Total:</strong></td>
                    <td><strong class="text-primary">LKR {{ number_format($order->total, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endif

@if($order->status_note)
<div class="mt-3">
    <h6 class="fw-bold mb-2">Status Note</h6>
    <div class="alert alert-info">
        {{ $order->status_note }}
    </div>
</div>
@endif

<script>
currentOrderId = {{ $order->id }};
</script>

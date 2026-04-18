<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">
                    <i class="bi bi-funnel me-2"></i>Filter Orders
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.orders.index') }}" method="GET">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="filterStatus" class="form-label fw-bold">Order Status</label>
                        <select class="form-select" name="status" id="filterStatus">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="filterPayment" class="form-label fw-bold">Payment Method</label>
                        <select class="form-select" name="payment_method" id="filterPayment">
                            <option value="">All Payment Methods</option>
                            <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>Cash on Delivery</option>
                            <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
                            <option value="bank" {{ request('payment_method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="mobile" {{ request('payment_method') == 'mobile' ? 'selected' : '' }}>Mobile Payment</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="filterDateFrom" class="form-label fw-bold">Date Range</label>
                        <div class="row">
                            <div class="col">
                                <input type="date" class="form-control" name="date_from" id="filterDateFrom" value="{{ request('date_from') }}">
                            </div>
                            <div class="col">
                                <input type="date" class="form-control" name="date_to" id="filterDateTo" value="{{ request('date_to') }}">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="filterAmountMin" class="form-label fw-bold">Amount Range (LKR)</label>
                        <div class="row">
                            <div class="col">
                                <input type="number" class="form-control" name="amount_min" id="filterAmountMin" placeholder="Min" value="{{ request('amount_min') }}">
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" name="amount_max" id="filterAmountMax" placeholder="Max" value="{{ request('amount_max') }}">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="filterSearch" class="form-label fw-bold">Customer Search</label>
                        <input type="text" class="form-control" name="search" id="filterSearch" placeholder="Search by name, email, or phone" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Clear Filters</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-2"></i>Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

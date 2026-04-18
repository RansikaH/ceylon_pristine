<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">
                    <i class="bi bi-download me-2"></i>Export Orders
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.orders.export') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="exportFormat" class="form-label fw-bold">Export Format</label>
                        <select class="form-select" name="format" id="exportFormat" required>
                            <option value="">Select Format</option>
                            <option value="csv">CSV File</option>
                            <option value="excel">Excel File</option>
                            <option value="pdf">PDF File</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="exportDateRange" class="form-label fw-bold">Date Range</label>
                        <select class="form-select" name="date_range" id="exportDateRange">
                            <option value="all">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="year">This Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    <div id="customDateRange" class="mb-3" style="display: none;">
                        <label class="form-label fw-bold">Custom Date Range</label>
                        <div class="row">
                            <div class="col">
                                <input type="date" class="form-control" name="export_date_from" placeholder="From">
                            </div>
                            <div class="col">
                                <input type="date" class="form-control" name="export_date_to" placeholder="To">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Export Options</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="include_customer_details" id="includeCustomerDetails" checked>
                            <label class="form-check-label" for="includeCustomerDetails">
                                Include Customer Details
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="include_items" id="includeItems" checked>
                            <label class="form-check-label" for="includeItems">
                                Include Order Items
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="include_address" id="includeAddress" checked>
                            <label class="form-check-label" for="includeAddress">
                                Include Shipping Address
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-download me-2"></i>Export Orders
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('exportDateRange').addEventListener('change', function() {
    const customDateRange = document.getElementById('customDateRange');
    if (this.value === 'custom') {
        customDateRange.style.display = 'block';
    } else {
        customDateRange.style.display = 'none';
    }
});
</script>
@endpush

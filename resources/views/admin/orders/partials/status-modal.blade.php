<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1" aria-labelledby="statusUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusUpdateModalLabel">
                    <i class="bi bi-arrow-repeat me-2"></i>Update Order Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusUpdateForm" action="{{ route('admin.orders.update-status') }}" method="POST">
                @csrf
                <input type="hidden" name="order_id" id="updateOrderId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select New Status</label>
                        <div class="d-grid gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusPending" value="pending">
                                <label class="form-check-label" for="statusPending">
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-clock me-1"></i>Pending
                                    </span>
                                    <small class="text-muted d-block">Order received, awaiting processing</small>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusProcessing" value="processing">
                                <label class="form-check-label" for="statusProcessing">
                                    <span class="badge bg-info">
                                        <i class="bi bi-gear me-1"></i>Processing
                                    </span>
                                    <small class="text-muted d-block">Order is being prepared</small>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusCompleted" value="completed">
                                <label class="form-check-label" for="statusCompleted">
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Completed
                                    </span>
                                    <small class="text-muted d-block">Order has been delivered</small>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusCancelled" value="cancelled">
                                <label class="form-check-label" for="statusCancelled">
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle me-1"></i>Cancelled
                                    </span>
                                    <small class="text-muted d-block">Order has been cancelled</small>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="statusNote" class="form-label fw-bold">Status Note (Optional)</label>
                        <textarea class="form-control" id="statusNote" name="note" rows="3" placeholder="Add a note about this status update..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('statusUpdateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('statusUpdateModal')).hide();
            location.reload();
        } else {
            alert('Error updating status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating status');
    });
});
</script>
@endpush

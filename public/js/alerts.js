// Professional Alert Functions using SweetAlert2

// Success Alert
function showSuccess(message, title = 'Success!') {
    Swal.fire({
        icon: 'success',
        title: title,
        text: message,
        showConfirmButton: false,
        timer: 3000,
        toast: true,
        position: 'top-end',
        background: '#d4edda',
        color: '#155724',
        iconColor: '#28a745'
    });
}

// Error Alert
function showError(message, title = 'Error!') {
    Swal.fire({
        icon: 'error',
        title: title,
        text: message,
        showConfirmButton: true,
        confirmButtonColor: '#dc3545',
        background: '#f8d7da',
        color: '#721c24',
        iconColor: '#dc3545'
    });
}

// Warning Alert
function showWarning(message, title = 'Warning!') {
    Swal.fire({
        icon: 'warning',
        title: title,
        text: message,
        showConfirmButton: true,
        confirmButtonColor: '#ffc107',
        background: '#fff3cd',
        color: '#856404',
        iconColor: '#ffc107'
    });
}

// Info Alert
function showInfo(message, title = 'Info') {
    Swal.fire({
        icon: 'info',
        title: title,
        text: message,
        showConfirmButton: true,
        confirmButtonColor: '#17a2b8',
        background: '#d1ecf1',
        color: '#0c5460',
        iconColor: '#17a2b8'
    });
}

// Confirmation Dialog
function showConfirmation(message, title = 'Are you sure?', confirmText = 'Yes, delete it!', cancelText = 'Cancel') {
    return Swal.fire({
        title: title,
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#dc3545',
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        background: '#fff',
        color: '#333'
    });
}

// Loading Alert
function showLoading(title = 'Loading...') {
    Swal.fire({
        title: title,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
}

// Toast Notification (general purpose)
function showToast(message, type = 'success', duration = 3000) {
    const toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: duration,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    const backgrounds = {
        success: '#d4edda',
        error: '#f8d7da',
        warning: '#fff3cd',
        info: '#d1ecf1'
    };

    const colors = {
        success: '#155724',
        error: '#721c24',
        warning: '#856404',
        info: '#0c5460'
    };

    toast.fire({
        icon: type,
        title: message,
        background: backgrounds[type] || '#d1ecf1',
        color: colors[type] || '#0c5460'
    });
}

// Example usage in forms:
// document.getElementById('deleteBtn').addEventListener('click', async function() {
//     const result = await showConfirmation('This action cannot be undone!', 'Delete this item?');
//     if (result.isConfirmed) {
//         // Perform delete action
//         showSuccess('Item deleted successfully!');
//     }
// });

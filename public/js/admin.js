/**
 * Admin Panel JavaScript
 * Handles sidebar toggle, charts, and other interactive elements
 */

document.addEventListener('DOMContentLoaded', function() {
    // Enable tooltips everywhere
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment below if you want to persist toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            // Uncomment below to store the preference in localStorage
            // localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

    // Initialize DataTables if present
    if (typeof simpleDatatables !== 'undefined') {
        const datatablesSimple = document.getElementById('datatablesSimple');
        if (datatablesSimple) {
            new simpleDatatables.DataTable(datatablesSimple, {
                perPage: 10,
                perPageSelect: [5, 10, 25, 50, 100],
                labels: {
                    placeholder: "Search...",
                    perPage: "{select} entries per page",
                    noRows: "No entries found",
                    info: "Showing {start} to {end} of {rows} entries"
                },
                classes: {
                    active: 'active',
                    disabled: 'disabled',
                    selector: 'form-select',
                    paginationList: 'pagination',
                    paginationListItem: 'page-item',
                    paginationListItemLink: 'page-link'
                }
            });
        }
    }

    // Initialize charts if Chart.js is available
    if (typeof Chart !== 'undefined') {
        // Example chart initialization
        const ctx = document.getElementById('myChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Sales',
                        data: [12, 19, 3, 5, 2, 3],
                        backgroundColor: 'rgba(54, 185, 204, 0.5)',
                        borderColor: 'rgba(54, 185, 204, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    }

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Activate current nav item
    const currentLocation = location.href;
    const menuItems = document.querySelectorAll('.sb-sidenav .nav-link');
    const menuLength = menuItems.length;
    
    for (let i = 0; i < menuLength; i++) {
        if (menuItems[i].href === currentLocation) {
            menuItems[i].classList.add('active');
            // Also activate parent dropdown if exists
            const parentItem = menuItems[i].closest('.collapse');
            if (parentItem) {
                parentItem.classList.add('show');
                const parentLink = document.querySelector(`[data-bs-target="#${parentItem.id}"]`);
                if (parentLink) {
                    parentLink.classList.add('active');
                    parentLink.setAttribute('aria-expanded', 'true');
                }
            }
            break;
        }
    }
});

// Add active class to current nav item in dropdowns
const dropdownItems = document.querySelectorAll('.dropdown-item');
dropdownItems.forEach(item => {
    if (item.href === window.location.href) {
        item.classList.add('active');
    }
});

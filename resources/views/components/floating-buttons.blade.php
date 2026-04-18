<!-- Floating Action Buttons Component -->
<style>
    .floating-buttons-container {
        position: fixed !important;
        bottom: 20px !important;
        right: 20px !important;
        z-index: 999999 !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 12px !important;
    }
    
    .floating-btn {
        width: 60px !important;
        height: 60px !important;
        border-radius: 50% !important;
        border: 3px solid white !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3) !important;
        transition: all 0.3s ease !important;
        text-decoration: none !important;
        font-size: 24px !important;
        font-weight: bold !important;
    }
    
    .floating-btn:hover {
        transform: scale(1.1) !important;
        box-shadow: 0 6px 20px rgba(0,0,0,0.4) !important;
    }
    
    .btn-back-to-top {
        background: #1f2937 !important;
        color: white !important;
    }
    
    .btn-facebook {
        background: #1877f2 !important;
        color: white !important;
    }
    
    .btn-email {
        background: #dc2626 !important;
        color: white !important;
    }
    
    .btn-call {
        background: #16a34a !important;
        color: white !important;
    }
</style>

<div class="floating-buttons-container">
    <!-- Back to Top Button -->
    <button 
        class="floating-btn btn-back-to-top" 
        onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
        title="Back to top"
        id="backToTopBtn"
    >
        ↑
    </button>
    
    <!-- Facebook Button -->
    <a 
        href="https://facebook.com" 
        target="_blank" 
        rel="noopener noreferrer"
        class="floating-btn btn-facebook"
        title="Visit our Facebook page"
    >
        f
    </a>
    
    <!-- Email Button -->
    <a 
        href="mailto:info@ceylonmoms.com" 
        class="floating-btn btn-email"
        title="Send us an email"
    >
        ✉
    </a>
    
    <!-- Call Button -->
    <a 
        href="tel:+94123456789" 
        class="floating-btn btn-call"
        title="Call us"
    >
        📞
    </a>
</div>

<script>
    // Show/hide back to top button based on scroll
    window.addEventListener('scroll', function() {
        const backBtn = document.getElementById('backToTopBtn');
        if (backBtn) {
            if (window.pageYOffset > 300) {
                backBtn.style.opacity = '1';
            } else {
                backBtn.style.opacity = '0.7';
            }
        }
    });
</script>

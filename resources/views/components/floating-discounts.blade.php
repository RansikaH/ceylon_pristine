<!-- Floating Discounts Card Component -->
<style>
    .floating-discounts-container {
        position: fixed !important;
        left: 0 !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        z-index: 99998 !important;
        transition: all 0.4s ease !important;
    }
    
    .discount-tab {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%) !important;
        color: white !important;
        padding: 15px 10px !important;
        border-radius: 0 10px 10px 0 !important;
        cursor: pointer !important;
        box-shadow: 2px 2px 15px rgba(0,0,0,0.3) !important;
        writing-mode: vertical-rl !important;
        text-orientation: mixed !important;
        font-weight: bold !important;
        font-size: 16px !important;
        letter-spacing: 2px !important;
        transition: all 0.3s ease !important;
        border: none !important;
        display: flex !important;
        align-items: center !important;
        gap: 5px !important;
    }
    
    .discount-tab:hover {
        background: linear-gradient(135deg, #ff5252 0%, #e04a5f 100%) !important;
        padding-right: 15px !important;
    }
    
    .discount-panel {
        position: absolute !important;
        left: 0 !important;
        top: 50% !important;
        transform: translateY(-50%) translateX(-100%) !important;
        width: 350px !important;
        max-height: 80vh !important;
        background: white !important;
        border-radius: 0 15px 15px 0 !important;
        box-shadow: 3px 0 20px rgba(0,0,0,0.2) !important;
        overflow: hidden !important;
        transition: all 0.4s ease !important;
    }
    
    .discount-panel.active {
        transform: translateY(-50%) translateX(0) !important;
    }
    
    .discount-header {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%) !important;
        color: white !important;
        padding: 20px !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
    }
    
    .discount-header h3 {
        margin: 0 !important;
        font-size: 20px !important;
        font-weight: bold !important;
    }
    
    .close-btn {
        background: rgba(255,255,255,0.2) !important;
        border: none !important;
        color: white !important;
        width: 30px !important;
        height: 30px !important;
        border-radius: 50% !important;
        cursor: pointer !important;
        font-size: 20px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.3s ease !important;
    }
    
    .close-btn:hover {
        background: rgba(255,255,255,0.3) !important;
        transform: rotate(90deg) !important;
    }
    
    .discount-content {
        padding: 15px !important;
        max-height: calc(80vh - 80px) !important;
        overflow-y: auto !important;
    }
    
    .discount-product-card {
        background: #f8f9fa !important;
        border-radius: 10px !important;
        padding: 12px !important;
        margin-bottom: 12px !important;
        display: flex !important;
        gap: 12px !important;
        transition: all 0.3s ease !important;
        border: 2px solid transparent !important;
        text-decoration: none !important;
        color: inherit !important;
    }
    
    .discount-product-card:hover {
        background: white !important;
        border-color: #ff6b6b !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        transform: translateX(5px) !important;
    }
    
    .product-image {
        width: 80px !important;
        height: 80px !important;
        object-fit: cover !important;
        border-radius: 8px !important;
        flex-shrink: 0 !important;
    }
    
    .product-info {
        flex: 1 !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
    }
    
    .product-name {
        font-weight: 600 !important;
        font-size: 14px !important;
        margin-bottom: 5px !important;
        color: #2c3e50 !important;
        display: -webkit-box !important;
        -webkit-line-clamp: 2 !important;
        -webkit-box-orient: vertical !important;
        overflow: hidden !important;
    }
    
    .product-prices {
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        margin-bottom: 5px !important;
    }
    
    .original-price {
        text-decoration: line-through !important;
        color: #999 !important;
        font-size: 13px !important;
    }
    
    .discounted-price {
        color: #ff6b6b !important;
        font-weight: bold !important;
        font-size: 16px !important;
    }
    
    .discount-badge {
        background: #ff6b6b !important;
        color: white !important;
        padding: 3px 8px !important;
        border-radius: 5px !important;
        font-size: 11px !important;
        font-weight: bold !important;
    }
    
    .no-discounts {
        text-align: center !important;
        padding: 40px 20px !important;
        color: #999 !important;
    }
    
    .pulse-animation {
        animation: pulse 2s infinite !important;
    }
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }
    
    /* Scrollbar styling */
    .discount-content::-webkit-scrollbar {
        width: 6px !important;
    }
    
    .discount-content::-webkit-scrollbar-track {
        background: #f1f1f1 !important;
    }
    
    .discount-content::-webkit-scrollbar-thumb {
        background: #ff6b6b !important;
        border-radius: 3px !important;
    }
    
    .discount-content::-webkit-scrollbar-thumb:hover {
        background: #ee5a6f !important;
    }
</style>

<div class="floating-discounts-container">
    <!-- Expandable Tab -->
    <button class="discount-tab pulse-animation" onclick="toggleDiscountPanel()">
        <span>🔥</span>
        <span>LATEST DISCOUNTS</span>
    </button>
    
    <!-- Discount Panel -->
    <div class="discount-panel" id="discountPanel">
        <div class="discount-header">
            <h3>🎉 Latest Discounts</h3>
            <button class="close-btn" onclick="toggleDiscountPanel()">×</button>
        </div>
        
        <div class="discount-content">
            @php
                $discountedProducts = \App\Models\Product::where('discount_percentage', '>', 0)
                    ->orderBy('discount_percentage', 'desc')
                    ->take(10)
                    ->get();
            @endphp
            
            @if($discountedProducts->count() > 0)
                @foreach($discountedProducts as $product)
                    <a href="{{ route('shop.product', $product) }}" class="discount-product-card">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                        @else
                            <img src="{{ asset('images/default-product.png') }}" alt="{{ $product->name }}" class="product-image">
                        @endif
                        
                        <div class="product-info">
                            <div class="product-name">{{ $product->name }}</div>
                            <div class="product-prices">
                                <span class="original-price">Rs. {{ number_format($product->price, 2) }}</span>
                                <span class="discounted-price">Rs. {{ number_format($product->discounted_price, 2) }}</span>
                            </div>
                            <div>
                                <span class="discount-badge">{{ $product->discount_percentage }}% OFF</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            @else
                <div class="no-discounts">
                    <p style="font-size: 48px; margin-bottom: 10px;">🛍️</p>
                    <p style="font-weight: 600; margin-bottom: 5px;">No Active Discounts</p>
                    <p style="font-size: 14px;">Check back soon for amazing deals!</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function toggleDiscountPanel() {
        const panel = document.getElementById('discountPanel');
        panel.classList.toggle('active');
    }
    
    // Close panel when clicking outside
    document.addEventListener('click', function(event) {
        const container = document.querySelector('.floating-discounts-container');
        const panel = document.getElementById('discountPanel');
        
        if (panel.classList.contains('active') && !container.contains(event.target)) {
            panel.classList.remove('active');
        }
    });
    
    // Prevent closing when clicking inside the panel
    document.getElementById('discountPanel').addEventListener('click', function(event) {
        event.stopPropagation();
    });
</script>

<header class="bg-brand-secondary text-white py-3 shadow" style="background-color: #c52b36;">
    <div class="container d-flex flex-wrap align-items-center justify-content-between">
        <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
            <img src="{{ asset('logo/logo.png') }}" alt="VegiShop Logo" height="40" class="me-2">
        </a>
        <nav class="nav gap-2">
            <a href="/" class="nav-link px-3 text-white fw-semibold position-relative {{ request()->is('/') ? 'active' : '' }}">Home</a>
            <a href="{{ route('shop.full') }}" class="nav-link px-3 text-white fw-semibold position-relative {{ request()->is('shop/full') ? 'active' : '' }}">Shop All</a>
            <a href="/cart" class="nav-link px-3 text-white fw-semibold position-relative {{ request()->is('cart') ? 'active' : '' }}">Cart @if($cartTotal > 0)<span class="badge rounded-pill bg-light text-danger ms-1">LKR {{ number_format($cartTotal, 2) }}</span>@endif</a>
            @auth
                <a href="{{ url('/dashboard') }}" class="nav-link px-3 text-white fw-semibold position-relative {{ request()->is('dashboard*') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('my.orders.index') }}" class="nav-link px-3 text-white fw-semibold position-relative {{ request()->is('my/orders*') ? 'active' : '' }}">My Orders</a>
                <form method="POST" action="/logout" class="d-inline ms-2">
                    @csrf
                    <button type="submit" class="btn px-3" style="background-color: #c52b36; color: #fff; border: none; border-radius: 4px;">Logout</button>
                </form>
            @else
                <a href="/login" class="btn px-3 ms-2" style="background-color: #fff; color: #c52b36; border: 2px solid #c52b36; border-radius: 4px;">Login</a>
                <a href="/register" class="btn px-3 ms-2" style="background-color: #c52b36; color: #fff; border: none; border-radius: 4px;">Register</a>
            @endauth
        </nav>
    </div>
</header>


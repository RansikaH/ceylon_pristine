<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Gate;
use App\Policies\OrderPolicy;
use App\Models\Order;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        
        // Register policies
        Gate::policy(Order::class, OrderPolicy::class);
        
        // Share cart total value with all views
        View::composer('*', function ($view) {
            $cart = Session::get('cart', []);
            $cartTotal = 0;
            
            foreach ($cart as $item) {
                $cartTotal += $item['price'] * $item['quantity'];
            }
            
            $view->with('cartTotal', $cartTotal);
        });
    }
}

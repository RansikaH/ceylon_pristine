<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \Log::info('AdminMiddleware: Checking user authentication', [
            'url' => $request->fullUrl(),
            'user_authenticated' => auth()->check(),
            'user_id' => auth()->check() ? auth()->id() : null,
            'session_id' => session()->getId(),
            'request_path' => $request->path()
        ]);

        if (!auth()->check()) {
            \Log::info('AdminMiddleware: User not authenticated, redirecting to admin.login');
            return redirect()->route('admin.login')
                ->with('error', 'Please login to access the admin area.');
        }

        // Check if the user has admin role (TEMPORARILY DISABLED FOR TESTING)
        $user = auth()->user();
        \Log::info('AdminMiddleware: User authenticated', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role ?? 'no_role',
            'expected_role' => 'admin'
        ]);

        // TEMPORARILY COMMENT OUT ROLE CHECK
        // if ($user->role !== 'admin') {
        //     \Log::info('AdminMiddleware: User does not have admin role, logging out and redirecting');
        //     auth()->logout();
        //     return redirect()->route('admin.login')
        //         ->with('error', 'You do not have permission to access the admin area.');
        // }
        
        \Log::info('AdminMiddleware: Access granted (role check disabled for testing), proceeding to next request');
        return $next($request);
    }
}

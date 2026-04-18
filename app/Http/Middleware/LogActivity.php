<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Only log if user is authenticated and not an AJAX request
        if (auth()->check() && !$request->ajax() && !$request->isMethod('GET')) {
            $this->logActivity($request, $response);
        }
        
        return $response;
    }
    
    /**
     * Log the activity based on the request.
     */
    protected function logActivity(Request $request, Response $response)
    {
        $user = auth()->user();
        $route = $request->route();
        
        if (!$route) {
            return;
        }
        
        $routeName = $route->getName();
        $action = $this->getActionFromRoute($routeName);
        
        // Skip logging for certain routes
        if ($this->shouldSkipLogging($routeName, $request)) {
            return;
        }
        
        $description = $this->getDescriptionFromRequest($request, $response);
        $subject = $this->getSubjectFromRequest($request);
        
        // Log the activity
        ActivityLogger::log($action, $description, $subject, [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'route_name' => $routeName,
            'status_code' => $response->getStatusCode(),
        ]);
    }
    
    /**
     * Determine the action type from route name.
     */
    protected function getActionFromRoute(?string $routeName): string
    {
        if (!$routeName) {
            return 'unknown';
        }
        
        if (str_contains($routeName, 'login')) {
            return 'login';
        }
        
        if (str_contains($routeName, 'logout')) {
            return 'logout';
        }
        
        if (str_contains($routeName, 'store') || str_contains($routeName, 'create')) {
            return 'created';
        }
        
        if (str_contains($routeName, 'update') || str_contains($routeName, 'edit')) {
            return 'updated';
        }
        
        if (str_contains($routeName, 'destroy') || str_contains($routeName, 'delete')) {
            return 'deleted';
        }
        
        if (str_contains($routeName, 'password')) {
            return 'password_changed';
        }
        
        if (str_contains($routeName, 'profile')) {
            return 'profile_updated';
        }
        
        return 'action_performed';
    }
    
    /**
     * Check if the activity should be skipped.
     */
    protected function shouldSkipLogging(?string $routeName, Request $request): bool
    {
        if (!$routeName) {
            return true;
        }
        
        // Skip activity log routes to prevent infinite loops
        if (str_contains($routeName, 'activity-logs')) {
            return true;
        }
        
        // Skip notification routes
        if (str_contains($routeName, 'notifications')) {
            return true;
        }
        
        // Skip dashboard views (too many requests)
        if (str_contains($routeName, 'dashboard') && $request->isMethod('GET')) {
            return true;
        }
        
        // Skip file downloads and exports
        if (str_contains($routeName, 'export') || str_contains($routeName, 'download')) {
            return true;
        }
        
        // Skip status endpoints (AJAX heavy)
        if (str_contains($routeName, 'status') || str_contains($routeName, 'details')) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Get description from the request.
     */
    protected function getDescriptionFromRequest(Request $request, Response $response): string
    {
        $route = $request->route();
        $routeName = $route?->getName();
        
        if (!$routeName) {
            return 'Performed unknown action';
        }
        
        $userType = auth()->guard('admin')->check() ? 'Admin' : 'User';
        
        // Descriptions for common actions
        $descriptions = [
            'products.store' => 'Created new product',
            'products.update' => 'Updated product information',
            'products.destroy' => 'Deleted a product',
            'categories.store' => 'Created new category',
            'categories.update' => 'Updated category information',
            'categories.destroy' => 'Deleted a category',
            'admin.orders.update-status' => 'Updated order status',
            'profile.update' => 'Updated profile information',
            'customers.message' => 'Sent message to customer',
        ];
        
        return $descriptions[$routeName] ?? "Performed action on {$routeName}";
    }
    
    /**
     * Get the subject model from the request.
     */
    protected function getSubjectFromRequest(Request $request)
    {
        $route = $request->route();
        
        if (!$route) {
            return null;
        }
        
        // Get model from route parameters
        $parameters = $route->parameters();
        
        // Return the first model parameter
        foreach ($parameters as $parameter) {
            if ($parameter instanceof \Illuminate\Database\Eloquent\Model) {
                return $parameter;
            }
        }
        
        return null;
    }
}

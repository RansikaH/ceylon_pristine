<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request): View
    {
        $query = ActivityLog::with(['user', 'subject'])
            ->orderBy('created_at', 'desc');

        // Filter by action
        if ($request->filled('action')) {
            $query->forAction($request->input('action'));
        }

        // Filter by user type
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->input('user_type'));
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->input('date_to') . ' 23:59:59');
        }

        // Search by description
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('description', 'like', "%{$search}%");
        }

        $activities = $query->paginate(50)->withQueryString();

        // Get available actions for filter
        $availableActions = ActivityLog::distinct('action')
            ->pluck('action')
            ->sort()
            ->values();

        // Get available user types
        $availableUserTypes = ActivityLog::distinct('user_type')
            ->whereNotNull('user_type')
            ->pluck('user_type')
            ->map(function ($type) {
                return class_basename($type);
            })
            ->sort()
            ->values();

        return view('admin.activity-logs.index', compact(
            'activities',
            'availableActions',
            'availableUserTypes'
        ));
    }

    /**
     * Display the specified activity log.
     */
    public function show(ActivityLog $activityLog): View
    {
        $activityLog->load(['user', 'subject']);
        
        return view('admin.activity-logs.show', compact('activityLog'));
    }

    /**
     * Get activity logs as JSON for AJAX requests.
     */
    public function json(Request $request): JsonResponse
    {
        $query = ActivityLog::with(['user', 'subject'])
            ->orderBy('created_at', 'desc');

        // Apply same filters as index method
        if ($request->filled('action')) {
            $query->forAction($request->input('action'));
        }
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->input('user_type'));
        }
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->input('date_to') . ' 23:59:59');
        }
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('description', 'like', "%{$search}%");
        }

        $activities = $query->limit(100)->get();

        return response()->json([
            'data' => $activities->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'user' => $activity->user ? [
                        'name' => $activity->user->name ?? 'Unknown',
                        'type' => class_basename($activity->user_type),
                    ] : null,
                    'action' => $activity->action,
                    'description' => $activity->description,
                    'subject' => $activity->subject ? [
                        'type' => class_basename($activity->subject_type),
                        'id' => $activity->subject_id,
                    ] : null,
                    'ip_address' => $activity->ip_address,
                    'created_at' => $activity->created_at->format('M d, Y H:i:s'),
                ];
            })
        ]);
    }

    /**
     * Clear old activity logs.
     */
    public function clear(Request $request): JsonResponse
    {
        $request->validate([
            'days' => 'required|integer|min:1|max:365',
        ]);

        $days = $request->input('days');
        $deleted = ActivityLog::where('created_at', '<', now()->subDays($days))->delete();

        return response()->json([
            'success' => true,
            'message' => "Deleted {$deleted} activity logs older than {$days} days."
        ]);
    }

    /**
     * Export activity logs to CSV.
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with(['user', 'subject'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('action')) {
            $query->forAction($request->input('action'));
        }
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->input('user_type'));
        }
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->input('date_to') . ' 23:59:59');
        }

        $activities = $query->get();

        $filename = 'activity-logs-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($activities) {
            $file = fopen('php://output', 'w');
            
            // CSV header
            fputcsv($file, [
                'ID',
                'User',
                'User Type',
                'Action',
                'Description',
                'Subject Type',
                'Subject ID',
                'IP Address',
                'User Agent',
                'Created At'
            ]);

            // CSV rows
            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->id,
                    $activity->user ? $activity->user->name ?? 'Unknown' : 'System',
                    class_basename($activity->user_type),
                    $activity->action,
                    $activity->description,
                    $activity->subject_type ? class_basename($activity->subject_type) : '',
                    $activity->subject_id,
                    $activity->ip_address,
                    $activity->user_agent,
                    $activity->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

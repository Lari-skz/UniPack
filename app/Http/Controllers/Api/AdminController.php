<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    /**
     * GET /api/admin/stats - Dashboard statistics
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_users' => User::count(),
            'total_tasks' => Task::count(),
            'total_events' => Event::count(),
            'total_categories' => Category::count(),
            'active_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
            'completed_tasks' => Task::where('status', 'completed')->count(),
            'pending_tasks' => Task::where('status', 'pending')->count(),
            'in_progress_tasks' => Task::where('status', 'in_progress')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ], 200);
    }

    /**
     * GET /api/admin/users - List all users
     */
    public function listUsers(): JsonResponse
    {
        $users = User::withCount(['tasks', 'events', 'categories'])
                     ->orderBy('created_at', 'desc')
                     ->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ], 200);
    }

    /**
     * GET /api/admin/users/{id} - Get specific user details
     */
    public function getUser(int $id): JsonResponse
    {
        $user = User::withCount(['tasks', 'events', 'categories'])->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    /**
     * DELETE /api/admin/users/{id} - Delete user
     */
    public function deleteUser(Request $request, int $id): JsonResponse
    {
        // Prevent admin from deleting themselves
        if ($request->user()->id === $id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account'
            ], 400);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Delete user (cascade will delete their tasks, events, categories)
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ], 200);
    }

    /**
     * PATCH /api/admin/users/{id}/toggle-admin - Toggle admin status
     */
    public function toggleAdminStatus(Request $request, int $id): JsonResponse
    {
        // Prevent admin from removing their own admin status
        if ($request->user()->id === $id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot modify your own admin status'
            ], 400);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => $user->is_admin ? 'User promoted to admin' : 'Admin privileges revoked',
            'data' => $user
        ], 200);
    }

    /**
     * GET /api/admin/recent-activity - Recent activity
     */
    public function recentActivity(): JsonResponse
    {
        $recentUsers = User::latest()->take(5)->get(['id', 'name', 'email', 'created_at']);
        $recentTasks = Task::with('user:id,name')->latest()->take(5)->get();
        $recentEvents = Event::with('user:id,name')->latest()->take(5)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'recent_users' => $recentUsers,
                'recent_tasks' => $recentTasks,
                'recent_events' => $recentEvents,
            ]
        ], 200);
    }
}

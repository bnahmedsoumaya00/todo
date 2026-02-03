<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Todo;

class AdminController extends Controller
{
    public function dashboard()
    {
        try {
            if (!Auth::check()) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            if (Auth::user()->role !== 'admin') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $totalUsers = User::count();
            $totalTodos = Todo::count();
            $completedTodos = Todo::where('completed', true)->count();
            $pendingTodos = Todo::where('completed', false)->count();

            $users = User::withCount('todos')
                ->orderBy('created_at', 'desc')
                ->get();

            $recentTodos = Todo::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'total_users' => $totalUsers,
                'total_todos' => $totalTodos,
                'completed_todos' => $completedTodos,
                'pending_todos' => $pendingTodos,
                'users' => $users,
                'recent_todos' => $recentTodos,
            ]);
        } catch (\Exception $e) {
            Log::error('Admin dashboard error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'message' => 'Error loading dashboard',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function statistics()
    {
        try {
            if (!Auth::check()) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }

            if (Auth::user()->role !== 'admin') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $dailyStats = Todo::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->get();

            $userCount = User::count();
            $todoCount = Todo::count();

            return response()->json([
                'daily_todos' => $dailyStats,
                'avg_todos_per_user' => $userCount > 0 ? round($todoCount / $userCount, 2) : 0,
            ]);
        } catch (\Exception $e) {
            Log::error('Admin statistics error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error loading statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

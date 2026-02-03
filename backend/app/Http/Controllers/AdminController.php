<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Todo;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!Auth::check()) {
            abort(401, 'Unauthenticated');
        }

        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $stats = Cache::remember('admin_dashboard_stats_' . now()->format('Y-m-d-H-i'), 300, function () {
            return [
                'total_users' => User::count(),
                'total_todos' => Todo::count(),
                'completed_todos' => Todo::where('completed', true)->count(),
                'pending_todos' => Todo::where('completed', false)->count(),
            ];

        });
        return response()->json([
            ...$stats,
            'users' => User::withCount('todos')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'todos_count' => $user->todos_count,
                        'created_at' => $user->created_at->toIso8601String(),
                    ];
                }),
            'recent_todos' => Todo::with('user:id,name')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($todo) {
                    return [
                        'id' => $todo->id,
                        'title' => $todo->title,
                        'completed' => $todo->completed,
                        'user' => [
                            'id' => $todo->user->id,
                            'name' => $todo->user->name,
                        ],
                        'created_at' => $todo->created_at->toIso8601String(),
                    ];
                }),
        ]);
    }
    public function statistics()
    {
        if (!Auth::check()) {
            abort(401, 'Unauthenticated');
        }
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $dailyStats = Todo::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'daily_todos' => $dailyStats,
            'avg_todos_per_user' => Todo::count() / max(User::count(), 1),
        ]);
    }
}

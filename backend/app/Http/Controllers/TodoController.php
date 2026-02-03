<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Services\TodoService;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    protected $todoService;

    public function __construct(TodoService $todoService)
    {
        $this->todoService = $todoService;
    }

    public function index()
    {
        $todos = $this->todoService->getAllTodos();
        return response()->json($todos);
    }

    public function store(TodoRequest $request)
    {
        $todo = $this->todoService->createTodo($request->validated());
        return response()->json($todo, 201);
    }

    public function show(Todo $todo)
    {
        if ($todo->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        return response()->json($todo);
    }

    public function update(TodoRequest $request, Todo $todo)
    {
        if ($todo->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        $updatedTodo = $this->todoService->updateTodo($todo, $request->validated());
        return response()->json($updatedTodo);
    }

    public function destroy(Todo $todo)
    {
        if ($todo->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        $this->todoService->deleteTodo($todo);
        return response()->json(null, 204);
    }
}

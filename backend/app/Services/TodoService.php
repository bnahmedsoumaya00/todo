<?php
namespace App\Services;

use App\Models\Todo;
use Illuminate\Support\Facades\Auth;


class TodoService {
    /**
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */

    public function getAllTodos(){
        return Auth::user()->todos()->orderBy('created_at', 'desc')->get();
    }

    /**
     * @param array $data
     * @return \App\Models\Todo
     */

    public function createTodo(array $data)
    {
        return Auth::user()->todos()->create([
            'title'=>$data['title'],
            'description'=>$data['description'] ?? null,
            'completed'=> $data['completed'] ?? false,

        ]);
    }
    public function updateTodo(Todo $todo, array $data)
    {
        $todo->update([
            'title'=>$data['title'] ?? $todo->title,
            'description'=>$data['description'] ?? $todo->description,
            'completed'=> $data['completed'] ?? $todo->completed,
        ]);
        return $todo;
    }

    /**
     *
     * @param \App\Models\Todo
     * @return bool
     */

    public function deleteTodo(Todo $todo){
        return $todo->delete();
    }
}

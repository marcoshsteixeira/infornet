<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TasksController extends Controller
{
    public function index() {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'completed' => 'required',
        ]);

        $task = Task::create($validatedData);

        return response()->json([
            'message' => 'Task created successfully',
            'id' => $task->id
        ], 201);
    }

    public function update(Request $request, $id) {
        $validatedData = $request->validate([
            'completed' => 'required',
        ]);

        $task = Task::findOrFail($id);

        $task->update($validatedData);

        return response()->json([
            'message' => 'Task updated successfully',
            'id' => $task->id
        ], 200);
    }
}

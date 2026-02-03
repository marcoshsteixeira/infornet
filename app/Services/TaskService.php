<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    public function getAllTasks(): Collection
    {
        return Task::all();
    }

    public function createTask(array $taskData): Task
    {
        return Task::create($taskData);
    }

    public function updateTask(Task $task, array $taskData): Task
    {
        $task->update($taskData);

        return $task;
    }
}

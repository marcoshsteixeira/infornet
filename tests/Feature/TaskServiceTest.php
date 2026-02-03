<?php

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->taskService = new TaskService();
});

// ==================== getAllTasks ====================

test('retorna todas as tarefas', function () {
    Task::factory()->count(3)->create();

    $tasks = $this->taskService->getAllTasks();

    expect($tasks)->toHaveCount(3);
});

test('retorna coleção vazia quando não há tarefas', function () {
    $tasks = $this->taskService->getAllTasks();

    expect($tasks)->toHaveCount(0);
});

// ==================== createTask ====================

test('cria uma tarefa com sucesso', function () {
    $taskData = [
        'title' => 'Nova Tarefa',
        'description' => 'Descrição da tarefa',
        'completed' => false,
    ];

    $task = $this->taskService->createTask($taskData);

    expect($task)->toBeInstanceOf(Task::class)
        ->and($task->title)->toBe('Nova Tarefa')
        ->and($task->description)->toBe('Descrição da tarefa')
        ->and($task->completed)->toBeFalse();
});

test('persiste a tarefa no banco de dados', function () {
    $taskData = [
        'title' => 'Tarefa Teste',
        'description' => 'Descrição teste',
        'completed' => true,
    ];

    $this->taskService->createTask($taskData);

    $this->assertDatabaseHas('tasks', [
        'title' => 'Tarefa Teste',
        'description' => 'Descrição teste',
        'completed' => true,
    ]);
});

// ==================== updateTask ====================

test('atualiza uma tarefa existente', function () {
    $task = Task::factory()->create([
        'title' => 'Tarefa Original',
        'completed' => false,
    ]);

    $updatedData = [
        'title' => 'Tarefa Atualizada',
        'completed' => true,
    ];

    $updatedTask = $this->taskService->updateTask($task, $updatedData);

    expect($updatedTask->title)->toBe('Tarefa Atualizada')
        ->and($updatedTask->completed)->toBeTrue();
});

test('persiste as alterações no banco de dados', function () {
    $task = Task::factory()->create(['completed' => false]);

    $this->taskService->updateTask($task, ['completed' => true]);

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'completed' => true,
    ]);
});

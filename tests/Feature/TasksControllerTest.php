<?php

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ==================== GET /api/tasks ====================

test('GET /api/tasks retorna lista vazia quando não há tarefas', function () {
    $response = $this->getJson('/api/tasks');

    $response->assertStatus(200)
        ->assertJson([]);
});

test('GET /api/tasks retorna todas as tarefas', function () {
    Task::factory()->count(3)->create();

    $response = $this->getJson('/api/tasks');

    $response->assertStatus(200)
        ->assertJsonCount(3);
});

test('GET /api/tasks retorna tarefas com estrutura correta', function () {
    $task = Task::factory()->create([
        'title' => 'Tarefa Teste',
        'description' => 'Descrição Teste',
        'completed' => false,
    ]);

    $response = $this->getJson('/api/tasks');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $task->id,
            'title' => 'Tarefa Teste',
            'description' => 'Descrição Teste',
            'completed' => false,
        ]);
});

// ==================== POST /api/tasks ====================

test('POST /api/tasks cria uma nova tarefa com sucesso', function () {
    $taskData = [
        'title' => 'Nova Tarefa',
        'description' => 'Descrição da nova tarefa',
        'completed' => false,
    ];

    $response = $this->postJson('/api/tasks', $taskData);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'title',
                'description',
                'completed',
                'created_at',
                'updated_at',
            ],
        ])
        ->assertJsonFragment([
            'message' => 'Task created successfully',
            'title' => 'Nova Tarefa',
        ]);
});

test('POST /api/tasks persiste a tarefa no banco de dados', function () {
    $taskData = [
        'title' => 'Tarefa Persistida',
        'description' => 'Descrição',
        'completed' => true,
    ];

    $this->postJson('/api/tasks', $taskData);

    $this->assertDatabaseHas('tasks', [
        'title' => 'Tarefa Persistida',
        'description' => 'Descrição',
        'completed' => true,
    ]);
});

test('POST /api/tasks falha quando título está ausente', function () {
    $taskData = [
        'description' => 'Descrição sem título',
        'completed' => false,
    ];

    $response = $this->postJson('/api/tasks', $taskData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

test('POST /api/tasks falha quando descrição está ausente', function () {
    $taskData = [
        'title' => 'Título sem descrição',
        'completed' => false,
    ];

    $response = $this->postJson('/api/tasks', $taskData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['description']);
});

test('POST /api/tasks falha quando completed não é boolean', function () {
    $taskData = [
        'title' => 'Tarefa',
        'description' => 'Descrição',
        'completed' => 'texto',
    ];

    $response = $this->postJson('/api/tasks', $taskData);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['completed']);
});

// ==================== PUT /api/tasks/{id} ====================

test('PUT /api/tasks/{id} atualiza uma tarefa com sucesso', function () {
    $task = Task::factory()->create([
        'title' => 'Tarefa Original',
        'completed' => false,
    ]);

    $updateData = [
        'completed' => true,
    ];

    $response = $this->putJson("/api/tasks/{$task->id}", $updateData);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'message' => 'Task updated successfully',
            'completed' => true,
        ]);
});

test('PUT /api/tasks/{id} persiste a atualização no banco de dados', function () {
    $task = Task::factory()->create(['completed' => false]);

    $this->putJson("/api/tasks/{$task->id}", ['completed' => true]);

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'completed' => true,
    ]);
});

test('PUT /api/tasks/{id} retorna 404 quando tarefa não existe', function () {
    $response = $this->putJson('/api/tasks/999', ['completed' => true]);

    $response->assertStatus(404);
});

test('PUT /api/tasks/{id} falha quando completed está ausente', function () {
    $task = Task::factory()->create();

    $response = $this->putJson("/api/tasks/{$task->id}", []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['completed']);
});

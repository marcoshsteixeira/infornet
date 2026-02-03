<?php

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('pode criar uma tarefa', function () {
    $task = Task::create([
        'title' => 'Tarefa Teste',
        'description' => 'Descrição',
        'completed' => false,
    ]);

    expect($task)->toBeInstanceOf(Task::class)
        ->and($task->exists)->toBeTrue();
});

it('converte completed para boolean', function () {
    $task = Task::factory()->create(['completed' => true]);

    expect($task->completed)->toBeBool()
        ->and($task->completed)->toBeTrue();
});

it('tem timestamps', function () {
    $task = Task::factory()->create();

    expect($task->created_at)->not->toBeNull()
        ->and($task->updated_at)->not->toBeNull();
});

it('permite mass assignment dos campos fillable', function () {
    $data = [
        'title' => 'Tarefa',
        'description' => 'Descrição',
        'completed' => true,
    ];

    $task = Task::create($data);

    expect($task->title)->toBe('Tarefa')
        ->and($task->description)->toBe('Descrição')
        ->and($task->completed)->toBeTrue();
});

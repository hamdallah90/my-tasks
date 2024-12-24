<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::put('tasks/update-priorities', [\App\Http\Controllers\TasksController::class, 'updatePriorities']);
Route::apiResource('tasks', \App\Http\Controllers\TasksController::class)->only(['index', 'store', 'update','destroy']);

Route::post('projects', \App\Http\Controllers\ProjectsController::class);
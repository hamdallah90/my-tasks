<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = Task::filter($request->all())->with('project')->get();

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks,
            'projects' => Project::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        Task::create($request->validated());

        return Inertia::location(route('tasks.index'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return Inertia::location(route('tasks.index'));
    }

    /**
     * Update the priorities of the tasks.
     * 
     * @param Request $request
     */
    public function updatePriorities(Request $request)
    {
        $validated = $request->validate([
            'taskOrder' => 'required|array',
            'taskOrder.*' => 'required|integer|exists:tasks,id',
        ]);

        // will be like [id1, id2, id3, id4, id5]
        $taskOrder = $validated['taskOrder'];

        foreach ($taskOrder as $index => $taskId) {
            Task::where('id', $taskId)->update(['priority' => $index + 1]);
        }

        return Inertia::location(route('tasks.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return Inertia::location(route('tasks.index'));
    }
}

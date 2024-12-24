<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // create a new project
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Project::create($validated);

        return Inertia::location(route('tasks.index'));
    }
}

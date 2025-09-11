<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Actions\Project\CreateProjectAction;
use App\Actions\Project\StoreProjectAction;
use App\Actions\Project\ShowProjectAction;
use App\Actions\Project\EditProjectAction;
use App\Actions\Project\UpdateProjectAction;
use App\Actions\Project\DestroyProjectAction;
use App\Actions\Project\BulkDestroyProjectAction;
use App\Actions\Project\GetProjectsIndexAction;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Models\Employee;
use Illuminate\Routing\Controller;

class ProjectController extends Controller
{
    public function index(Request $request, GetProjectsIndexAction $action)
    {
        $data = $action->getIndexData($request);
        if (!$data['success']) {
            return Inertia::render('Projects/Index', [
                'projects' => new ProjectCollection(collect()),
                'error' => $data['error'],
            ]);
        }
        return Inertia::render('Projects/Index', [
            'projects' => new ProjectCollection($data['projects']),
        ]);
    }

    public function create(CreateProjectAction $action)
    {
        $employees = Employee::all();
        $data = $action->execute([]);
        return Inertia::render('Projects/Create', [
            'employees' => $employees,
        ]);
    }

    public function store(StoreProjectRequest $request, StoreProjectAction $action)
    {
        $result = $action->execute($request->validated());
        if ($result['success']) {
            return redirect()->route('projects.index')
                ->with('success', $result['message']);
        }
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    public function show(string $id, ShowProjectAction $action)
    {
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('projects.index')
                ->with('error', $data['message']);
        }
        return Inertia::render('Projects/Show', $data);
    }

    public function edit(string $id, EditProjectAction $action)
    {
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('projects.index')
                ->with('error', $data['message']);
        }
        return Inertia::render('Projects/Edit', $data);
    }

    public function update(UpdateProjectRequest $request, string $id, UpdateProjectAction $action)
    {
        $result = $action->execute($id, $request->validated());
        if ($result['success']) {
            return redirect()->route('projects.index')
                ->with('success', $result['message']);
        }
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    public function destroy(string $id, DestroyProjectAction $action)
    {
        $result = $action->execute($id);
        return redirect()->route('projects.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function bulkDestroy(Request $request, BulkDestroyProjectAction $action)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:projects,id',
        ]);
        $result = $action->execute($request->input('ids'));
        return redirect()->route('projects.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}

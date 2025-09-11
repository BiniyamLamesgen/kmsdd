<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Actions\Experience\CreateExperienceAction;
use App\Actions\Experience\StoreExperienceAction;
use App\Actions\Experience\ShowExperienceAction;
use App\Actions\Experience\EditExperienceAction;
use App\Actions\Experience\UpdateExperienceAction;
use App\Actions\Experience\DestroyExperienceAction;
use App\Actions\Experience\BulkDestroyExperienceAction;
use App\Actions\Experience\GetExperiencesIndexAction;
use App\Http\Requests\StoreExperienceRequest;
use App\Http\Requests\UpdateExperienceRequest;
use App\Http\Resources\ExperienceCollection;
use App\Models\Employee;
use Illuminate\Routing\Controller;

class ExperienceController extends Controller
{
    public function index(Request $request, GetExperiencesIndexAction $action)
    {
        $data = $action->getIndexData($request);
        if (!$data['success']) {
            return Inertia::render('Experience/Index', [
                'experiences' => new ExperienceCollection(collect()),
                'error' => $data['error'],
            ]);
        }
        return Inertia::render('Experience/Index', [
            'experiences' => new ExperienceCollection($data['experiences']),
        ]);
    }

    public function create()
    {
        // provide a display 'name' (first + last) so the Vue select shows a proper label
        $employees = Employee::select('id', 'first_name', 'last_name')->get()
            ->map(function ($e) {
                $e->name = trim(($e->first_name ?? '') . ' ' . ($e->last_name ?? ''));
                return $e;
            })
            ->sortBy('name')
            ->values();

        return Inertia::render('Experience/Create', [
            'employees' => $employees,
        ]);
    }

    public function store(StoreExperienceRequest $request, StoreExperienceAction $action)
    {
        $res = $action->execute($request->validated());
        return $res['success']
            ? redirect()->route('experiences.index')->with('success', $res['message'])
            : back()->with('error', $res['message']);
    }

    public function show(string $id, ShowExperienceAction $action)
    {
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('experiences.index')
                ->with('error', $data['message']);
        }
        return Inertia::render('Experience/Show', $data);
    }

    public function edit(string $id, EditExperienceAction $action)
    {
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('experiences.index')
                ->with('error', $data['message']);
        }

        // provide the same employees list (with a display 'name') as create() so the Edit page can show a dropdown
        $employees = Employee::select('id', 'first_name', 'last_name', 'position', 'department')->get()
            ->map(function ($e) {
                $e->name = trim(($e->first_name ?? '') . ' ' . ($e->last_name ?? ''));
                return $e;
            })
            ->sortBy('name')
            ->values();

        // merge employees into data passed to the Inertia page
        return Inertia::render('Experience/Edit', array_merge($data, [
            'employees' => $employees,
        ]));
    }

    public function update(UpdateExperienceRequest $request, string $id, UpdateExperienceAction $action)
    {
        $result = $action->execute($id, $request->validated());
        if ($result['success']) {
            return redirect()->route('experiences.index')
                ->with('success', $result['message']);
        }
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    public function destroy(string $id, DestroyExperienceAction $action)
    {
        $result = $action->execute($id);
        return redirect()->route('experiences.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function bulkDestroy(Request $request, BulkDestroyExperienceAction $action)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:experiences,id',
        ]);
        $result = $action->execute($request->input('ids'));
        return redirect()->route('experiences.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}

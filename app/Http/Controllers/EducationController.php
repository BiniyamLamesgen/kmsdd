<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Actions\Education\CreateEducationAction;
use App\Actions\Education\StoreEducationAction;
use App\Actions\Education\ShowEducationAction;
use App\Actions\Education\EditEducationAction;
use App\Actions\Education\UpdateEducationAction;
use App\Actions\Education\DestroyEducationAction;
use App\Actions\Education\BulkDestroyEducationAction;
use App\Actions\Education\GetEducationIndexAction;
use App\Http\Requests\StoreEducationRequest;
use App\Http\Requests\UpdateEducationRequest;
use App\Http\Resources\EducationCollection;
use App\Models\Employee;
use App\Models\Education;
use Illuminate\Routing\Controller;

class EducationController extends Controller
{
    public function index(Request $request, GetEducationIndexAction $action)
    {
        $data = $action->getIndexData($request);
        if (!$data['success']) {
            return Inertia::render('Education/Index', [
                'education' => new EducationCollection(collect()),
                'error' => $data['error'],
            ]);
        }
        return Inertia::render('Education/Index', [
            'education' => new EducationCollection($data['education']),
        ]);
    }

    public function create(Request $request, CreateEducationAction $action)
    {
        $employees = Employee::all();
        $data = $action->execute($request->all());
        return Inertia::render('Education/Create', [
            ...$data,
            'employees' => $employees,
        ]);
    }

    public function store(StoreEducationRequest $request, StoreEducationAction $action)
    {
        $result = $action->execute($request->validated());
        if (is_array($result) && array_key_exists('success', $result) && $result['success']) {
            return redirect()->route('educations.index')
                ->with('success', $result['message'] ?? 'Education created successfully.');
        }
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message'] ?? 'Failed to create education.');
    }

    public function show(string $id, ShowEducationAction $action)
    {
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('educations.index')
                ->with('error', $data['message']);
        }
        return Inertia::render('Education/Show', $data);
    }

    public function edit(string $id, EditEducationAction $action)
    {
        $employees = Employee::all();
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('educations.index')
                ->with('error', $data['message']);
        }
        return Inertia::render('Education/Edit', [
            ...$data,
            'employees' => $employees,
        ]);
    }

    public function update(UpdateEducationRequest $request, string $id, UpdateEducationAction $action)
    {
        $result = $action->execute($id, $request->validated());
        if ($result['success']) {
            return redirect()->route('educations.index')
                ->with('success', $result['message']);
        }
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    public function destroy(string $id, DestroyEducationAction $action)
    {
        $result = $action->execute($id);
        return redirect()->route('educations.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function bulkDestroy(Request $request, BulkDestroyEducationAction $action)
    {
        // Validate payload without hitting the DB (avoid exists rule when table may not exist)
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $result = $action->execute($request->input('ids'));
        // Redirect back (safer than relying on a named route that might be missing)
        return redirect()->back()
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}


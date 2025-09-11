<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Actions\Endorsement\CreateEndorsementAction;
use App\Actions\Endorsement\StoreEndorsementAction;
use App\Actions\Endorsement\ShowEndorsementAction;
use App\Actions\Endorsement\EditEndorsementAction;
use App\Actions\Endorsement\UpdateEndorsementAction;
use App\Actions\Endorsement\DestroyEndorsementAction;
use App\Actions\Endorsement\BulkDestroyEndorsementAction;
use App\Actions\Endorsement\GetEndorsementsIndexAction;
use App\Http\Requests\StoreEndorsementRequest;
use App\Http\Requests\UpdateEndorsementRequest;
use App\Http\Resources\EndorsementCollection;
use App\Models\Employee;
use App\Models\Skill;
use Illuminate\Routing\Controller;

class EndorsementController extends Controller
{
    public function index(Request $request, GetEndorsementsIndexAction $action)
    {
        $data = $action->getIndexData($request);
        if (!$data['success']) {
            return Inertia::render('Endorsements/Index', [
                'endorsements' => new EndorsementCollection(collect()),
                'error' => $data['error'],
            ]);
        }
        return Inertia::render('Endorsements/Index', [
            'endorsements' => new EndorsementCollection($data['endorsements']),
        ]);
    }

    public function create(CreateEndorsementAction $action)
    {
        $employees = Employee::all();
        $skills = Skill::all();
        $data = $action->execute();
        return Inertia::render('Endorsements/Create', [
            'employees' => $employees,
            'skills' => $skills,
        ]);
    }

    public function store(StoreEndorsementRequest $request, StoreEndorsementAction $action)
    {
        $result = $action->execute($request->validated());
        if ($result['success']) {
            return redirect()->route('endorsements.index')
                ->with('success', $result['message']);
        }
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    public function show(string $id, ShowEndorsementAction $action)
    {
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('endorsements.index')
                ->with('error', $data['message']);
        }
        return Inertia::render('Endorsements/Show', $data);
    }

    public function edit(string $id, EditEndorsementAction $action)
    {
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('endorsements.index')
                ->with('error', $data['message']);
        }
        return Inertia::render('Endorsements/Edit', $data);
    }

    public function update(UpdateEndorsementRequest $request, string $id, UpdateEndorsementAction $action)
    {
        $result = $action->execute($id, $request->validated());
        if ($result['success']) {
            return redirect()->route('endorsements.index')
                ->with('success', $result['message']);
        }
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    public function destroy(string $id, DestroyEndorsementAction $action)
    {
        $result = $action->execute($id);
        return redirect()->route('endorsements.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function bulkDestroy(Request $request, BulkDestroyEndorsementAction $action)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:endorsements,id',
        ]);
        $result = $action->execute($request->input('ids'));
        return redirect()->route('endorsements.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Actions\KnowledgeSharing\CreateKnowledgeSharingAction;
use App\Actions\KnowledgeSharing\StoreKnowledgeSharingAction;
use App\Actions\KnowledgeSharing\ShowKnowledgeSharingAction;
use App\Actions\KnowledgeSharing\EditKnowledgeSharingAction;
use App\Actions\KnowledgeSharing\UpdateKnowledgeSharingAction;
use App\Actions\KnowledgeSharing\DestroyKnowledgeSharingAction;
use App\Actions\KnowledgeSharing\BulkDestroyKnowledgeSharingAction;
use App\Actions\KnowledgeSharing\GetKnowledgeSharingIndexAction;
use App\Http\Requests\StoreKnowledgeSharingRequest;
use App\Http\Requests\UpdateKnowledgeSharingRequest;
use App\Http\Resources\KnowledgeSharingCollection;
use App\Models\Employee;
use Illuminate\Routing\Controller;

class KnowledgeSharingController extends Controller
{
    public function index(Request $request, GetKnowledgeSharingIndexAction $action)
    {
        $data = $action->getIndexData($request);
        if (!$data['success']) {
            return Inertia::render('KnowledgeSharing/Index', [
                'knowledge_sharing' => new KnowledgeSharingCollection(collect()),
                'error' => $data['error'],
            ]);
        }
        return Inertia::render('KnowledgeSharing/Index', [
            'knowledge_sharing' => new KnowledgeSharingCollection($data['knowledge_sharing']),
        ]);
    }

    public function create(CreateKnowledgeSharingAction $action)
    {
        $employees = Employee::all();
        $data = $action->execute([]);
        return Inertia::render('KnowledgeSharing/Create', [
            'employees' => $employees,
        ]);
    }

    public function store(StoreKnowledgeSharingRequest $request, StoreKnowledgeSharingAction $action)
    {
        $result = $action->execute($request->validated());
        if ($result['success']) {
            return redirect()->route('knowledge-management.index')
                ->with('success', $result['message']);
        }
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    public function show(string $id, ShowKnowledgeSharingAction $action)
    {
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('knowledge-management.index')
                ->with('error', $data['message']);
        }
        return Inertia::render('KnowledgeSharing/Show', $data);
    }

    public function edit(string $id, EditKnowledgeSharingAction $action)
    {
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('knowledge-management.index')
                ->with('error', $data['message']);
        }
        return Inertia::render('KnowledgeSharing/Edit', $data);
    }

    public function update(UpdateKnowledgeSharingRequest $request, string $id, UpdateKnowledgeSharingAction $action)
    {
        $result = $action->execute($id, $request->validated());
        if ($result['success']) {
            return redirect()->route('knowledge-management.index')
                ->with('success', $result['message']);
        }
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    public function destroy(string $id, DestroyKnowledgeSharingAction $action)
    {
        $result = $action->execute($id);
        return redirect()->route('knowledge-management.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function bulkDestroy(Request $request, BulkDestroyKnowledgeSharingAction $action)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:knowledge_sharing,id',
        ]);
        $result = $action->execute($request->input('ids'));
        return redirect()->route('knowledge-management.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Actions\Skill\CreateSkillAction;
use App\Actions\Skill\StoreSkillAction;
use App\Actions\Skill\ShowSkillAction;
use App\Actions\Skill\EditSkillAction;
use App\Actions\Skill\UpdateSkillAction;
use App\Actions\Skill\DestroySkillAction;
use App\Actions\Skill\BulkDestroySkillAction;
use App\Actions\Skill\GetSkillsIndexAction;
use App\Http\Requests\StoreSkillRequest;
use App\Http\Requests\UpdateSkillRequest;
use App\Http\Resources\SkillCollection;
use Illuminate\Routing\Controller;

class SkillController extends Controller
{
    public function index(Request $request, GetSkillsIndexAction $action)
    {
        $data = $action->getIndexData($request);
        if (!$data['success']) {
            return Inertia::render('Skills/Index', [
                'skills' => new SkillCollection(collect()),
                'error' => $data['error'],
            ]);
        }
        return Inertia::render('Skills/Index', [
            'skills' => new SkillCollection($data['skills']),
        ]);
    }

    public function create(Request $request, CreateSkillAction $action)
    {
        $data = $action->execute($request->all());
        return Inertia::render('Skills/Create', $data);
    }

    public function store(StoreSkillRequest $request, StoreSkillAction $action)
    {
        $result = $action->execute($request->validated());
        if ($result['success']) {
            return redirect()->route('skills.index')
                ->with('success', $result['message']);
        }
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    public function show(string $id, ShowSkillAction $action)
    {
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('skills.index')
                ->with('error', $data['message']);
        }
        return Inertia::render('Skills/Show', $data);
    }

    public function edit(string $id, EditSkillAction $action)
    {
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('skills.index')
                ->with('error', $data['message']);
        }
        return Inertia::render('Skills/Edit', $data);
    }

    public function update(UpdateSkillRequest $request, string $id, UpdateSkillAction $action)
    {
        $result = $action->execute($id, $request->validated());
        if ($result['success']) {
            return redirect()->route('skills.index')
                ->with('success', $result['message']);
        }
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    public function destroy(string $id, DestroySkillAction $action)
    {
        $result = $action->execute($id);
        return redirect()->route('skills.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function bulkDestroy(Request $request, BulkDestroySkillAction $action)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:skills,id',
        ]);
        $result = $action->execute($request->input('ids'));
        return redirect()->route('skills.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Achievement;
use App\Http\Requests\StoreAchievementRequest;
use App\Http\Requests\UpdateAchievementRequest;
use App\Http\Resources\AchievementResource;
use App\Http\Resources\AchievementCollection;
use App\Actions\Achievement\CreateAchievementAction;
use App\Actions\Achievement\StoreAchievementAction;
use App\Actions\Achievement\ShowAchievementAction;
use App\Actions\Achievement\EditAchievementAction;
use App\Actions\Achievement\UpdateAchievementAction;
use App\Actions\Achievement\DestroyAchievementAction;
use App\Actions\Achievement\BulkDestroyAchievementAction;
use App\Actions\Achievement\GetAchievementsIndexAction;
use App\Models\Employee;
use Illuminate\Routing\Controller;

class AchievementController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, GetAchievementsIndexAction $action)
    {
        $data = $action->getIndexData($request);
        if (!$data['success']) {
            return Inertia::render('Achievements/Index', [
                'achievements' => new AchievementCollection(collect()),
                'error' => $data['error'],
            ]);
        }
        return Inertia::render('Achievements/Index', [
            'achievements' => new AchievementCollection($data['achievements']),
        ]);
    }

    public function create(CreateAchievementAction $action)
    {
        $employees = Employee::all();
        $data = $action->execute();
        return Inertia::render('Achievements/Create', [
            ...$data,
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAchievementRequest $request, StoreAchievementAction $action)
    {
        $result = $action->execute($request->validated());

        // Fix: Check if $result is an array and has 'success' key
        if (is_array($result) && array_key_exists('success', $result) && $result['success']) {
            return redirect()->route('achievements.index')
                ->with('success', $result['message'] ?? 'Achievement created successfully.');
        }
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message'] ?? 'Failed to create achievement.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, ShowAchievementAction $action)
    {
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('achievements.index')
                ->with('error', $data['message']);
        }
        return Inertia::render('Achievements/Show', $data);
    }

    public function edit(string $id, EditAchievementAction $action)
    {
        $employees = Employee::all();
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('achievements.index')
                ->with('error', $data['message']);
        }
        return Inertia::render('Achievements/Edit', [
            ...$data,
            'employees' => $employees,
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAchievementRequest $request, string $id, UpdateAchievementAction $action)
    {
        $result = $action->execute($id, $request->validated());
        if ($result['success']) {
            return redirect()->route('achievements.index')
                ->with('success', $result['message']);
        }
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, DestroyAchievementAction $action)
    {
        $result = $action->execute($id);
        return redirect()->route('achievements.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function bulkDestroy(Request $request, BulkDestroyAchievementAction $action)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:achievements,id',
        ]);
        $result = $action->execute($request->input('ids'));
        return redirect()->route('achievements.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}

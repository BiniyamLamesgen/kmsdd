<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Actions\EmployeeSkill\CreateEmployeeSkillAction;
use App\Actions\EmployeeSkill\StoreEmployeeSkillAction;
use App\Actions\EmployeeSkill\ShowEmployeeSkillAction;
use App\Actions\EmployeeSkill\EditEmployeeSkillAction;
use App\Actions\EmployeeSkill\UpdateEmployeeSkillAction;
use App\Actions\EmployeeSkill\DestroyEmployeeSkillAction;
use App\Actions\EmployeeSkill\BulkDestroyEmployeeSkillAction;
use App\Actions\EmployeeSkill\GetEmployeeSkillsIndexAction;
use App\Http\Requests\StoreEmployeeSkillRequest;
use App\Http\Requests\UpdateEmployeeSkillRequest;
use App\Http\Resources\EmployeeSkillCollection;
use App\Models\Employee;
use App\Models\Skill;
use Illuminate\Routing\Controller;

class EmployeeSkillController extends Controller
{
    public function index(Request $request, GetEmployeeSkillsIndexAction $action)
    {
        $data = $action->getIndexData($request);
        if (!$data['success']) {
            return Inertia::render('EmployeeSkills/Index', [
                'employeeSkills' => new EmployeeSkillCollection(collect()),
                'error' => $data['error'],
            ]);
        }
        return Inertia::render('EmployeeSkills/Index', [
            'employeeSkills' => new EmployeeSkillCollection($data['employeeSkills']),
        ]);
    }

    public function create(CreateEmployeeSkillAction $action)
    {
        $employees = Employee::all();
        $skills = Skill::all();
        $data = $action->execute();
        return Inertia::render('EmployeeSkills/Create', array_merge($data, [
            'employees' => $employees,
            'skills' => $skills,
        ]));
    }

    public function store(StoreEmployeeSkillRequest $request, StoreEmployeeSkillAction $action)
    {
        $result = $action->execute($request->validated());
        if ($result['success']) {
            return redirect()->route('employee-skills.index')
                ->with('success', $result['message']);
        }
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    public function show(string $id, ShowEmployeeSkillAction $action)
    {
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('employee-skills.index')
                ->with('error', $data['message']);
        }
        return Inertia::render('EmployeeSkills/Show', $data);
    }

    public function edit(string $id, EditEmployeeSkillAction $action)
    {
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('employee-skills.index')
                ->with('error', $data['message']);
        }
        return Inertia::render('EmployeeSkills/Edit', $data);
    }

    public function update(UpdateEmployeeSkillRequest $request, string $id, UpdateEmployeeSkillAction $action)
    {
        $result = $action->execute($id, $request->validated());
        if ($result['success']) {
            return redirect()->route('employee-skills.index')
                ->with('success', $result['message']);
        }
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    public function destroy(string $id, DestroyEmployeeSkillAction $action)
    {
        $result = $action->execute($id);
        return redirect()->route('employee-skills.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function bulkDestroy(Request $request, BulkDestroyEmployeeSkillAction $action)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:employee_skills,id',
        ]);
        $result = $action->execute($request->input('ids'));
        return redirect()->route('employee-skills.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Actions\Employee\CreateEmployeeAction;
use App\Actions\Employee\StoreEmployeeAction;
use App\Actions\Employee\ShowEmployeeAction;
use App\Actions\Employee\EditEmployeeAction;
use App\Actions\Employee\UpdateEmployeeAction;
use App\Actions\Employee\DestroyEmployeeAction;
use App\Actions\Employee\BulkDestroyEmployeeAction;
use App\Actions\Employee\GetEmployeesIndexAction;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeCollection;
use App\Models\Department;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function index(Request $request, GetEmployeesIndexAction $action)
    {
        $data = $action->getIndexData($request);

        if (!$data['success']) {
            return Inertia::render('Employees/Index', [
                'employees' => new EmployeeCollection(collect()),
                'error' => $data['error'],
            ]);
        }

        $employeesCollection = new EmployeeCollection($data['employees']);

        // Debug: Log the collection metadata
        logger()->info('EmployeeController Index Debug', [
            'employees_meta' => $data['employees']->toArray()['meta'] ?? 'No meta',
            'collection_meta' => $employeesCollection->toArray($request)['meta'] ?? 'No collection meta',
        ]);

        return Inertia::render('Employees/Index', [
            'employees' => $employeesCollection,
        ]);
    }

    public function create(CreateEmployeeAction $action)
    {
        $departments = Department::all();
        $data = $action->execute();
        return Inertia::render('Employees/Create', [
            'departments' => $departments,
        ]);
    }

    public function store(StoreEmployeeRequest $request, StoreEmployeeAction $action)
    {
        // Debug logging to see what's being received
        Log::info('Store Employee Request Data:', [
            'validated_data' => $request->validated(),
            'has_profile_picture' => $request->hasFile('profile_picture'),
            'has_profile_picture_file' => $request->hasFile('profile_picture_file'),
            'profile_picture_info' => $request->hasFile('profile_picture') ? [
                'name' => $request->file('profile_picture')->getClientOriginalName(),
                'size' => $request->file('profile_picture')->getSize(),
                'mime' => $request->file('profile_picture')->getMimeType(),
            ] : null,
            'profile_picture_file_info' => $request->hasFile('profile_picture_file') ? [
                'name' => $request->file('profile_picture_file')->getClientOriginalName(),
                'size' => $request->file('profile_picture_file')->getSize(),
                'mime' => $request->file('profile_picture_file')->getMimeType(),
            ] : null,
            'all_files' => $request->allFiles(),
        ]);

        // Check for both possible file field names
        $profilePicture = $request->file('profile_picture') ?: $request->file('profile_picture_file');

        $result = $action->execute($request->validated(), $profilePicture);

        if ($result['success']) {
            return redirect()->route('employees.index')
                ->with('success', $result['message']);
        }
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    public function show(string $id, ShowEmployeeAction $action)
    {
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('employees.index')
                ->with('error', $data['message']);
        }
        return Inertia::render('Employees/Show', $data);
    }

    public function edit(string $id, EditEmployeeAction $action)
    {
        $departments = Department::all();
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('employees.index')
                ->with('error', $data['message']);
        }
        return Inertia::render('Employees/Edit', array_merge($data, [
            'departments' => $departments,
        ]));
    }

    public function update(UpdateEmployeeRequest $request, string $id, UpdateEmployeeAction $action)
    {
        // dd($request->file('profile_picture'));
        Log::info('profile_picture', ['file' => $request->file('profile_picture')]);
        $result = $action->execute($id, $request->validated(), $request->file('profile_picture'));
        if ($result['success']) {
            return redirect()->route('employees.index')
                ->with('success', $result['message']);
        }
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    public function destroy(string $id, DestroyEmployeeAction $action)
    {
        $result = $action->execute($id);
        return redirect()->route('employees.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function bulkDestroy(Request $request, BulkDestroyEmployeeAction $action)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:employees,id',
        ]);
        $result = $action->execute($request->input('ids'));
        return redirect()->route('employees.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Actions\Department\BulkDeleteDepartmentAction;
use App\Actions\Department\DestroyDepartmentAction;
use App\Actions\Department\IndexDepartmentAction;
use App\Actions\Department\RestoreDepartmentAction;
use App\Actions\Department\ShowDepartmentAction;
use App\Actions\Department\StoreDepartmentAction;
use App\Actions\Department\UpdateDepartmentAction;
use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Inertia\Inertia;

class DepartmentController extends Controller
{
    public function index(IndexDepartmentAction $IndexDepartmentAction, Request $request)
    {
        try {
            return Inertia::render('Department/Index', [
                'departments' => $IndexDepartmentAction->paginated($request), // plain paginator
            ]);
        } catch (\Exception $e) {
            return $this->handleIndexError($e);
        }
    }

    protected function handleIndexError(\Exception $e)
    {
        Log::error('Error loading departments: ' . $e->getMessage());

        return Inertia::render('Department/Index', [
            'departments' => [
                'data' => [],
                'total' => 0,
                'current_page' => 1,
                'per_page' => 10,
                'last_page' => 1,
            ],
            'error' => 'Unable to load departments. Please try again later.',
        ]);
    }
    public function create()
    {

        return Inertia::render('Department/CreateDepartment');
    }


    public function store(DepartmentRequest $request, StoreDepartmentAction $storeDepartmentAction): RedirectResponse
    {
        $result = $storeDepartmentAction->execute($request->validated());

        if ($result['success']) {
            return redirect()->route('departments.index')->with('success', $result['message']);
        } else {
            return back()->withInput()->with('error', $result['message']);
        }
    }
    public function edit(Department $department)
    {
        return Inertia::render('Department/EditDepartment', [
            'department' => new DepartmentResource($department),
        ]);
    }

    public function show(Department $department)
    {
        return Inertia::render('Department/EditDepartment', [
            'department' => new DepartmentResource($department),
        ]);
    }
    public function update(DepartmentRequest $request, Department $department, UpdateDepartmentAction $updateDepartmentAction): RedirectResponse
    {
        $result = $updateDepartmentAction->execute($department->id, $request->validated());

        if ($result['success']) {
            return redirect()->route('departments.index')->with('success', $result['message']);
        } else {
            return back()->withInput()->with('error', $result['message']);
        }
    }
    public function destroy($idOrSlug, ShowDepartmentAction $showDepartmentAction, DestroyDepartmentAction $destroyDepartmentAction)
    {
        $department = $showDepartmentAction->show($idOrSlug);
        $result = $destroyDepartmentAction->execute($department);

        if ($result['success']) {
            return redirect()->route('departments.index')->with('success', $result['message']);
        } else {
            return back()->with('error', $result['message']);
        }
    }

    public function trash()
    {
        $trashedDepartments = Department::onlyTrashed()->paginate(10);

        $statistics = [
            'total_trashed' => Department::onlyTrashed()->count(),
            'total_active' => Department::count(), // optional
        ];

        return inertia('Department/Trash', [
            'trashedDepartments' => $trashedDepartments,
            'statistics' => $statistics, // 👈 ensure this is passed
        ]);
    }
    public function restore($id, RestoreDepartmentAction $restoreAction)
    {
        $result = $restoreAction->execute($id);

        return redirect()->route('departments.trash.index')->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }

    public function bulkRestore(Request $request, RestoreDepartmentAction $restoreAction)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:departments,id',
        ]);

        $result = $restoreAction->bulkRestore($request->input('ids'));

        return redirect()->route('departments.trash.index')->with(
            $result['success'] ? 'success' : 'warning',
            $result['message']
        );
    }

    public function bulkDestroy(Request $request, BulkDeleteDepartmentAction $bulkDeleteAction)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:departments,id',
        ]);

        $result = $bulkDeleteAction->execute($request->input('ids'));

        if ($result['success'] && $result['failed'] === 0) {
            return redirect()->route('departments.index')->with('success', $result['message']);
        } elseif ($result['success'] && $result['failed'] > 0) {
            return redirect()->route('departments.index')->with('warning', $result['message']);
        } else {
            return redirect()->route('departments.index')->with('error', $result['message']);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Actions\Employee\GetEmployeesIndexAction;

use App\Http\Resources\EmployeeCollection;
use Illuminate\Routing\Controller;
use Inertia\Inertia;

class FrontPageController extends Controller
{
    public function index(
        GetEmployeesIndexAction $getEmployeesIndexAction,
    ) {
        $request = request();
        $page = $request->input('page', 1);
        $perPage = $request->input('perPage', 3);

        $employeesData = $getEmployeesIndexAction->getIndexData($request);

        return Inertia::render('FrontPage', [
            'employees' => new EmployeeCollection($employeesData['success'] ? $employeesData['employees'] : collect()),
            'departments' => $employeesData['success'] ? $employeesData['departments'] : [],
        ]);
    }
}


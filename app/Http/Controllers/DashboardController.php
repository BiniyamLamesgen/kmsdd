<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use App\Models\Employee;
use App\Models\EmployeeSkill;
use App\Models\Skill;
use App\Models\Endorsement;
use App\Models\KnowledgeSharing;
use App\Models\Project;
use App\Models\Achievement;
use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'employees' => [
                'total' => Employee::count(),
                'recent' => Employee::with('department')
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get()
                    ->map(function ($employee) {
                        return [
                            'id' => $employee->id,
                            'first_name' => $employee->first_name,
                            'last_name' => $employee->last_name,
                            'position' => $employee->position,
                            'department' => $employee->department ? $employee->department->name : null,
                            'created_at' => $employee->created_at,
                        ];
                    }),
            ],
            'skills' => [
                'total' => Skill::count(),
                'top' => Skill::withCount('employees')->orderByDesc('employees_count')->take(5)->get(['id', 'skill_name']),
            ],
            'employeeSkills' => [
                'total' => EmployeeSkill::count(),
            ],
            'endorsements' => [
                'total' => Endorsement::count(),
                'recent' => Endorsement::with(['employee', 'skill', 'endorsedBy'])->orderBy('created_at', 'desc')->take(5)->get(),
            ],
            'knowledgeSharing' => [
                'total' => KnowledgeSharing::count(),
                'recent' => KnowledgeSharing::orderBy('created_at', 'desc')->take(5)->get(['id', 'employee_id', 'document_title', 'document_type', 'created_at']),
            ],
            'projects' => [
                'total' => Project::count(),
                'recent' => Project::orderBy('created_at', 'desc')->take(5)->get(['id', 'project_name', 'employee_id', 'role', 'created_at']),
            ],
            'achievements' => [
                'total' => Achievement::count(),
                'recent' => Achievement::orderBy('created_at', 'desc')->take(5)->get(['id', 'title', 'employee_id', 'date_awarded', 'created_at']),
            ],
            'certifications' => [
                'total' => Certification::count(),
                'recent' => Certification::orderBy('created_at', 'desc')->take(5)->get(['id', 'certification_name', 'employee_id', 'issue_date', 'expiry_date', 'created_at']),
            ],
            'education' => [
                'total' => Education::count(),
                'recent' => Education::orderBy('created_at', 'desc')->take(5)->get([
                    'id',
                    'employee_id',
                    'field_of_study',
                    'institution',
                    'graduation_year',
                    'created_at'
                ]),
            ],
            'experiences' => [
                'total' => Experience::count(),
                'recent' => Experience::orderBy('created_at', 'desc')->take(5)->get(['id', 'company_name', 'employee_id', 'position', 'start_date', 'end_date', 'created_at']),
            ],
        ];

        return Inertia::render('Dashboard', [
            'stats' => $stats,
        ]);
    }
}

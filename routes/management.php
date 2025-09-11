<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\EmployeeSkillController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\CertificationController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\KnowledgeManagementController;
use App\Http\Controllers\EndorsementController;
use App\Http\Controllers\KnowledgeSharingController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\GalleryController;

// Employee Management
Route::prefix('employees')->name('employees.')->middleware(['auth'])
    ->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('/create', [EmployeeController::class, 'create'])->name('create');
        Route::post('/', [EmployeeController::class, 'store'])->name('store');
        Route::post('/bulk-destroy', [EmployeeController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::get('/{employee}', [EmployeeController::class, 'show'])->name('show');
        Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
        Route::post('/{employee}', [EmployeeController::class, 'update'])->name('update');
        Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
    });

// Experience
Route::prefix('experiences')->name('experiences.')->middleware(['auth'])
    ->group(function () {
        Route::get('/', [ExperienceController::class, 'index'])->name('index');
        Route::get('/create', [ExperienceController::class, 'create'])->name('create');
        Route::post('/', [ExperienceController::class, 'store'])->name('store');
        Route::post('/bulk-destroy', [ExperienceController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::get('/{experience}', [ExperienceController::class, 'show'])->name('show');
        Route::get('/{experience}/edit', [ExperienceController::class, 'edit'])->name('edit');
        Route::post('/{experience}', [ExperienceController::class, 'update'])->name('update');
        Route::delete('/{experience}', [ExperienceController::class, 'destroy'])->name('destroy');
    });

// Projects
Route::prefix('projects')->name('projects.')->middleware(['auth'])
    ->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::get('/create', [ProjectController::class, 'create'])->name('create');
        Route::post('/', [ProjectController::class, 'store'])->name('store');
        Route::post('/bulk-destroy', [ProjectController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::get('/{project}', [ProjectController::class, 'show'])->name('show');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('edit');
        Route::post('/{project}', [ProjectController::class, 'update'])->name('update');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');
    });

// Skills
Route::prefix('skills')->name('skills.')->middleware(['auth'])
    ->group(function () {
        Route::get('/', [SkillController::class, 'index'])->name('index');
        Route::get('/create', [SkillController::class, 'create'])->name('create');
        Route::post('/', [SkillController::class, 'store'])->name('store');
        Route::post('/bulk-destroy', [SkillController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::get('/{skill}', [SkillController::class, 'show'])->name('show');
        Route::get('/{skill}/edit', [SkillController::class, 'edit'])->name('edit');
        Route::post('/{skill}', [SkillController::class, 'update'])->name('update');
        Route::delete('/{skill}', [SkillController::class, 'destroy'])->name('destroy');
    });

// Employee Skills
Route::prefix('employee-skills')->name('employee-skills.')->middleware(['auth'])
    ->group(function () {
        Route::get('/', [EmployeeSkillController::class, 'index'])->name('index');
        Route::get('/create', [EmployeeSkillController::class, 'create'])->name('create');
        Route::post('/', [EmployeeSkillController::class, 'store'])->name('store');
        Route::post('/bulk-destroy', [EmployeeSkillController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::get('/{employeeSkill}', [EmployeeSkillController::class, 'show'])->name('show');
        Route::get('/{employeeSkill}/edit', [EmployeeSkillController::class, 'edit'])->name('edit');
        Route::post('/{employeeSkill}', [EmployeeSkillController::class, 'update'])->name('update');
        Route::delete('/{employeeSkill}', [EmployeeSkillController::class, 'destroy'])->name('destroy');
    });

// Achievements
Route::prefix('achievements')->name('achievements.')->middleware(['auth'])
    ->group(function () {
        Route::get('/', [AchievementController::class, 'index'])->name('index');
        Route::get('/create', [AchievementController::class, 'create'])->name('create');
        Route::post('/', [AchievementController::class, 'store'])->name('store');
        Route::post('/bulk-destroy', [AchievementController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::get('/{achievement}', [AchievementController::class, 'show'])->name('show');
        Route::get('/{achievement}/edit', [AchievementController::class, 'edit'])->name('edit');
        Route::post('/{achievement}', [AchievementController::class, 'update'])->name('update');
        Route::delete('/{achievement}', [AchievementController::class, 'destroy'])->name('destroy');
    });

// Certifications
Route::prefix('certifications')->name('certifications.')->middleware(['auth'])
    ->group(function () {
        Route::get('/', [CertificationController::class, 'index'])->name('index');
        Route::get('/create', [CertificationController::class, 'create'])->name('create');
        Route::post('/', [CertificationController::class, 'store'])->name('store');
        Route::post('/bulk-destroy', [CertificationController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::get('/{certification}', [CertificationController::class, 'show'])->name('show');
        Route::get('/{certification}/edit', [CertificationController::class, 'edit'])->name('edit');
        Route::post('/{certification}', [CertificationController::class, 'update'])->name('update');
        Route::delete('/{certification}', [CertificationController::class, 'destroy'])->name('destroy');
    });

// Education
Route::prefix('educations')->name('educations.')->middleware(['auth'])
    ->group(function () {
        Route::get('/', [EducationController::class, 'index'])->name('index');
        Route::get('/create', [EducationController::class, 'create'])->name('create');
        Route::post('/', [EducationController::class, 'store'])->name('store');
        Route::post('/bulk-destroy', [EducationController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::get('/{education}', [EducationController::class, 'show'])->name('show');
        Route::get('/{education}/edit', [EducationController::class, 'edit'])->name('edit');
        Route::post('/{education}', [EducationController::class, 'update'])->name('update');
        Route::delete('/{education}', [EducationController::class, 'destroy'])->name('destroy');
    });

// Knowledge Management
Route::prefix('knowledge-management')->name('knowledge-management.')->middleware(['auth'])
    ->group(function () {
        Route::get('/', [KnowledgeSharingController::class, 'index'])->name('index');
        Route::get('/create', [KnowledgeSharingController::class, 'create'])->name('create');
        Route::post('/', [KnowledgeSharingController::class, 'store'])->name('store');
        Route::post('/bulk-destroy', [KnowledgeSharingController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::get('/{knowledge}', [KnowledgeSharingController::class, 'show'])->name('show');
        Route::get('/{knowledge}/edit', [KnowledgeSharingController::class, 'edit'])->name('edit');
        Route::post('/{knowledge}', [KnowledgeSharingController::class, 'update'])->name('update');
        Route::delete('/{knowledge}', [KnowledgeSharingController::class, 'destroy'])->name('destroy');
    });

// Endorsements
Route::prefix('endorsements')->name('endorsements.')->middleware(['auth'])
    ->group(function () {
        Route::get('/', [EndorsementController::class, 'index'])->name('index');
        Route::get('/create', [EndorsementController::class, 'create'])->name('create');
        Route::post('/', [EndorsementController::class, 'store'])->name('store');
        Route::post('/bulk-destroy', [EndorsementController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::get('/{endorsement}', [EndorsementController::class, 'show'])->name('show');
        Route::get('/{endorsement}/edit', [EndorsementController::class, 'edit'])->name('edit');
        Route::post('/{endorsement}', [EndorsementController::class, 'update'])->name('update');
        Route::delete('/{endorsement}', [EndorsementController::class, 'destroy'])->name('destroy');
    });
//     Route::get('/departments/trash', [DepartmentController::class, 'trash'])->name('departments.trash.index');
// Route::post('/departments/bulk-delete', [DepartmentController::class, 'bulkDestroy'])->name('departments.bulk-destroy');
// Route::post('/departments/{id}/restore', [DepartmentController::class, 'restore'])->name('departments.trash.restore');
// Route::post('/departments/trash/bulk-restore', [DepartmentController::class, 'bulkRestore'])->name('departments.trash.bulk-restore');

// Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
// Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
// Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');

// // 💥 Put 'edit' route BEFORE 'show'
// Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
// Route::get('/departments/{department}', [DepartmentController::class, 'show'])->name('departments.show');

// Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
// Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

Route::prefix('departments')->name('departments.')->middleware(['auth'])
    ->group(function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('index');
        Route::get('/create', [DepartmentController::class, 'create'])->name('create');
        Route::post('/', [DepartmentController::class, 'store'])->name('store');
        Route::post('/bulk-destroy', [DepartmentController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::get('/{department}', [DepartmentController::class, 'show'])->name('show');
        Route::get('/{department}/edit', [DepartmentController::class, 'edit'])->name('edit');
        Route::post('/{department}', [DepartmentController::class, 'update'])->name('update');
        Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('gallery')->name('gallery.')->middleware(['auth'])
        ->group(function () {
            Route::get('/', [GalleryController::class, 'index'])->name('index');
            Route::get('/create', [GalleryController::class, 'create'])->name('create');
            Route::post('/', [GalleryController::class, 'store'])->name('store');
            Route::post('/bulk-destroy', [GalleryController::class, 'bulkDestroy'])->name('bulk-destroy');
            Route::get('/{gallery}', [GalleryController::class, 'show'])->name('show');
            Route::get('/{gallery}/edit', [GalleryController::class, 'edit'])->name('edit');
            Route::post('/{gallery}', [GalleryController::class, 'update'])->name('update');
            Route::delete('/{gallery}', [GalleryController::class, 'destroy'])->name('destroy');
        });


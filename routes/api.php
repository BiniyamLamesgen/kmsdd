<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\EmployeeSkillController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\CertificationController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\KnowledgeSharingController;
use App\Http\Controllers\EndorsementController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\PasswordController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes (Public - no middleware)
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);

    // Protected auth routes (use web middleware for session-based auth)
    Route::middleware('web')->group(function () {
        Route::get('user', [AuthController::class, 'user']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
    });
});

// Protected API Routes (use web middleware for session-based auth)
Route::middleware('web')->group(function () {

    // Employee Routes
    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeeController::class, 'index']);
        Route::post('/', [EmployeeController::class, 'store']);
        Route::get('/departments', [EmployeeController::class, 'getDepartments']);
        Route::get('/available-for-sharing', [EmployeeController::class, 'getAvailableForSharing']);
        Route::get('/{employee}', [EmployeeController::class, 'show']);
        Route::put('/{employee}', [EmployeeController::class, 'update']);
        Route::delete('/{employee}', [EmployeeController::class, 'destroy']);
        Route::post('/{employee}/toggle-sharing', [EmployeeController::class, 'toggleSharingAvailability']);

        // Employee role management
        Route::post('/{employee}/roles', [RolePermissionController::class, 'assignRole']);
        Route::delete('/{employee}/roles', [RolePermissionController::class, 'removeRole']);
        Route::get('/{employee}/roles', [RolePermissionController::class, 'getUserRoles']);
        Route::get('/{employee}/permissions', [RolePermissionController::class, 'getUserPermissions']);
    });

    // Experience Routes
    Route::prefix('experiences')->group(function () {
        Route::get('/', [ExperienceController::class, 'index']);
        Route::post('/', [ExperienceController::class, 'store']);
        Route::get('/current', [ExperienceController::class, 'getCurrentExperiences']);
        Route::get('/employee/{employeeId}', [ExperienceController::class, 'getByEmployee']);
        Route::get('/{experience}', [ExperienceController::class, 'show']);
        Route::put('/{experience}', [ExperienceController::class, 'update']);
        Route::delete('/{experience}', [ExperienceController::class, 'destroy']);
    });

    // Project Routes
    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectController::class, 'index']);
        Route::post('/', [ProjectController::class, 'store']);
        Route::get('/ongoing', [ProjectController::class, 'getOngoingProjects']);
        Route::get('/completed', [ProjectController::class, 'getCompletedProjects']);
        Route::get('/upcoming', [ProjectController::class, 'getUpcomingProjects']);
        Route::get('/employee/{employeeId}', [ProjectController::class, 'getByEmployee']);
        Route::get('/{project}', [ProjectController::class, 'show']);
        Route::put('/{project}', [ProjectController::class, 'update']);
        Route::delete('/{project}', [ProjectController::class, 'destroy']);
    });

    // Skill Routes
    Route::prefix('skills')->group(function () {
        Route::get('/', [SkillController::class, 'index']);
        Route::post('/', [SkillController::class, 'store']);
        Route::get('/popular', [SkillController::class, 'getPopular']);
        Route::get('/search', [SkillController::class, 'search']);
        Route::get('/unused', [SkillController::class, 'getUnusedSkills']);
        Route::get('/employee/{employeeId}', [SkillController::class, 'getByEmployee']);
        Route::get('/{skill}', [SkillController::class, 'show']);
        Route::put('/{skill}', [SkillController::class, 'update']);
        Route::delete('/{skill}', [SkillController::class, 'destroy']);
    });

    // Employee Skills Routes
    Route::prefix('employee-skills')->group(function () {
        Route::get('/', [EmployeeSkillController::class, 'index']);
        Route::post('/', [EmployeeSkillController::class, 'store']);
        Route::get('/top-endorsed', [EmployeeSkillController::class, 'getTopEndorsed']);
        Route::get('/proficiency-stats', [EmployeeSkillController::class, 'getProficiencyStats']);
        Route::get('/endorsement-stats', [EmployeeSkillController::class, 'getEndorsementStats']);
        Route::get('/employee/{employeeId}', [EmployeeSkillController::class, 'getByEmployee']);
        Route::get('/skill/{skillId}', [EmployeeSkillController::class, 'getBySkill']);
        Route::get('/{employeeSkill}', [EmployeeSkillController::class, 'show']);
        Route::put('/{employeeSkill}', [EmployeeSkillController::class, 'update']);
        Route::delete('/{employeeSkill}', [EmployeeSkillController::class, 'destroy']);
        Route::post('/{employeeSkill}/endorse', [EmployeeSkillController::class, 'endorse']);
    });

    // Achievement Routes
    Route::prefix('achievements')->group(function () {
        Route::get('/', [AchievementController::class, 'index']);
        Route::post('/', [AchievementController::class, 'store']);
        Route::get('/recent', [AchievementController::class, 'getRecentAchievements']);
        Route::get('/top-achievers', [AchievementController::class, 'getTopAchievers']);
        Route::get('/stats', [AchievementController::class, 'getStats']);
        Route::get('/years', [AchievementController::class, 'getAchievementYears']);
        Route::get('/year/{year}', [AchievementController::class, 'getAchievementsByYear']);
        Route::get('/employee/{employeeId}', [AchievementController::class, 'getByEmployee']);
        Route::get('/{achievement}', [AchievementController::class, 'show']);
        Route::put('/{achievement}', [AchievementController::class, 'update']);
        Route::delete('/{achievement}', [AchievementController::class, 'destroy']);
    });

    // Certification Routes
    Route::prefix('certifications')->group(function () {
        Route::get('/', [CertificationController::class, 'index']);
        Route::post('/', [CertificationController::class, 'store']);
        Route::get('/expiring', [CertificationController::class, 'getExpiring']);
        Route::get('/expired', [CertificationController::class, 'getExpired']);
        Route::get('/stats', [CertificationController::class, 'getStats']);
        Route::get('/organizations', [CertificationController::class, 'getOrganizations']);
        Route::get('/years', [CertificationController::class, 'getCertificationYears']);
        Route::get('/employee/{employeeId}', [CertificationController::class, 'getByEmployee']);
        Route::get('/{certification}', [CertificationController::class, 'show']);
        Route::put('/{certification}', [CertificationController::class, 'update']);
        Route::delete('/{certification}', [CertificationController::class, 'destroy']);
    });

    // Education Routes
    Route::prefix('education')->group(function () {
        Route::get('/', [EducationController::class, 'index']);
        Route::post('/', [EducationController::class, 'store']);
        Route::get('/recent-graduates', [EducationController::class, 'getRecentGraduates']);
        Route::get('/stats', [EducationController::class, 'getStats']);
        Route::get('/institutions', [EducationController::class, 'getInstitutions']);
        Route::get('/fields-of-study', [EducationController::class, 'getFieldsOfStudy']);
        Route::get('/graduation-years', [EducationController::class, 'getGraduationYears']);
        Route::get('/institution/{institution}', [EducationController::class, 'getByInstitution']);
        Route::get('/level/{level}', [EducationController::class, 'getByEducationLevel']);
        Route::get('/employee/{employeeId}', [EducationController::class, 'getByEmployee']);
        Route::get('/{education}', [EducationController::class, 'show']);
        Route::put('/{education}', [EducationController::class, 'update']);
        Route::delete('/{education}', [EducationController::class, 'destroy']);
    });

    // Knowledge Sharing Routes
    Route::prefix('knowledge-sharing')->group(function () {
        Route::get('/', [KnowledgeSharingController::class, 'index']);
        Route::post('/', [KnowledgeSharingController::class, 'store']);
        Route::get('/recent', [KnowledgeSharingController::class, 'getRecentShares']);
        Route::get('/with-links', [KnowledgeSharingController::class, 'getWithLinks']);
        Route::get('/top-contributors', [KnowledgeSharingController::class, 'getTopContributors']);
        Route::get('/stats', [KnowledgeSharingController::class, 'getStats']);
        Route::get('/document-types', [KnowledgeSharingController::class, 'getDocumentTypes']);
        Route::get('/years', [KnowledgeSharingController::class, 'getSharingYears']);
        Route::get('/type/{type}', [KnowledgeSharingController::class, 'getByDocumentType']);
        Route::get('/year/{year}', [KnowledgeSharingController::class, 'getByYear']);
        Route::get('/employee/{employeeId}', [KnowledgeSharingController::class, 'getByEmployee']);
        Route::get('/{knowledgeSharing}', [KnowledgeSharingController::class, 'show']);
        Route::put('/{knowledgeSharing}', [KnowledgeSharingController::class, 'update']);
        Route::delete('/{knowledgeSharing}', [KnowledgeSharingController::class, 'destroy']);
    });

    // Endorsement Routes
    Route::prefix('endorsements')->group(function () {
        Route::get('/', [EndorsementController::class, 'index']);
        Route::post('/', [EndorsementController::class, 'store']);
        Route::get('/recent', [EndorsementController::class, 'getRecentEndorsements']);
        Route::get('/top-endorsed-employees', [EndorsementController::class, 'getTopEndorsedEmployees']);
        Route::get('/top-endorsers', [EndorsementController::class, 'getTopEndorsers']);
        Route::get('/most-endorsed-skills', [EndorsementController::class, 'getMostEndorsedSkills']);
        Route::get('/stats', [EndorsementController::class, 'getStats']);
        Route::get('/years', [EndorsementController::class, 'getEndorsementYears']);
        Route::get('/year/{year}', [EndorsementController::class, 'getByYear']);
        Route::get('/employee/{employeeId}', [EndorsementController::class, 'getByEmployee']);
        Route::get('/given-by/{employeeId}', [EndorsementController::class, 'getGivenByEmployee']);
        Route::get('/skill/{skillId}', [EndorsementController::class, 'getBySkill']);
        Route::get('/{endorsement}', [EndorsementController::class, 'show']);
        Route::put('/{endorsement}', [EndorsementController::class, 'update']);
        Route::delete('/{endorsement}', [EndorsementController::class, 'destroy']);
    });

    // Role and Permission Management Routes
    Route::prefix('roles')->group(function () {
        Route::get('/', [RolePermissionController::class, 'roles']);
        Route::post('/', [RolePermissionController::class, 'createRole']);
        Route::put('/{role}', [RolePermissionController::class, 'updateRole']);
        Route::delete('/{role}', [RolePermissionController::class, 'deleteRole']);
        Route::post('/{role}/permissions', [RolePermissionController::class, 'givePermissionToRole']);
        Route::delete('/{role}/permissions', [RolePermissionController::class, 'revokePermissionFromRole']);
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', [RolePermissionController::class, 'permissions']);
    });
});

<?php

// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Inertia\Inertia;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\FrontPageController;

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('frontpage', [FrontPageController::class, 'index'])->name('frontpage');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Make sure you have this for update:
// Route::post('/experiences/{id}', [ExperienceController::class, 'update'])->name('experiences.update');
// Or, if you want to support PUT:
Route::put('/experiences/{id}', [ExperienceController::class, 'update'])->name('experiences.update');
Route::post('/experiences/{id}', [ExperienceController::class, 'update'])->name('experiences.update');

Route::resource('certifications', \App\Http\Controllers\CertificationController::class);

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/management.php';
require __DIR__ . '/hero_slides.php';

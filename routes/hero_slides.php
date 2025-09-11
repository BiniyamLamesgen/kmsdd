<?php

use App\Http\Controllers\HeroSlide\HeroSlideController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('hero-slides', [HeroSlideController::class, 'index'])->name('hero-slides.index');
    Route::get('hero-slides/create', [HeroSlideController::class, 'create'])->name('hero-slides.create');
    Route::get('hero-slides/{heroSlide}', [HeroSlideController::class, 'show'])->name('hero-slides.show');
    Route::post('hero-slides', [HeroSlideController::class, 'store'])->name('hero-slides.store');
    Route::delete('hero-slides/bulk-destroy', [HeroSlideController::class, 'bulkDestroy'])->name('hero-slides.bulk-destroy');
    Route::get('hero-slides/{heroSlide}/edit', [HeroSlideController::class, 'edit'])->name('hero-slides.edit');
    Route::post('hero-slides/{heroSlide}/update', [HeroSlideController::class, 'update'])->name('hero-slides.update');
    Route::delete('hero-slides/{heroSlide}', [HeroSlideController::class, 'destroy'])->name('hero-slides.destroy');
});


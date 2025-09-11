<?php

namespace App\Http\Controllers;

use App\Actions\Employee\GetEmployeesIndexAction;
use App\Actions\Gallery\GetGalleryIndexAction;
use App\Actions\HeroSlide\GetHeroSlidesAction;
use App\Http\Resources\EmployeeCollection;
use App\Http\Resources\GalleryCollection;
use App\Http\Resources\HeroSlide\HeroSlideCollection;
use Illuminate\Routing\Controller;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(

        GetEmployeesIndexAction $getEmployeesIndexAction,
        GetHeroSlidesAction $getHeroSlidesAction,
        GetGalleryIndexAction $getGalleryIndexAction,

    ) {
        $request = request();

        return Inertia::render('Home', [
            'employees' => new EmployeeCollection($getEmployeesIndexAction->getIndexData($request)['success'] ? $getEmployeesIndexAction->getIndexData($request)['employees'] : collect()),
            'heroSlides' => new HeroSlideCollection($getHeroSlidesAction->getIndexData($request)['success'] ? $getHeroSlidesAction->getIndexData($request)['heroSlides'] : collect()),
            'gallery' => new GalleryCollection($getGalleryIndexAction->getIndexData($request)['success'] ? $getGalleryIndexAction->getIndexData($request)['gallery'] : collect()),

        ]);
    }
    //    
}

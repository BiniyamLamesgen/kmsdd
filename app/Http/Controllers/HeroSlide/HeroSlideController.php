<?php

namespace App\Http\Controllers\HeroSlide;

use App\Actions\HeroSlide\BulkDeleteHeroSlideAction;
use App\Actions\HeroSlide\GetHeroSlidesAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\HeroSlide\BulkDeleteHeroSlideRequest;
use App\Http\Requests\HeroSlide\HeroSlideStoreRequest;
use App\Http\Requests\HeroSlide\HeroSlideUpdateRequest;
use App\Models\HeroSlide;
use App\Http\Requests\HeroSlideRequest;
use App\Http\Resources\HeroSlide\HeroSlideCollection;
use App\Http\Resources\HeroSlide\HeroSlideResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as RoutingController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class HeroSlideController extends RoutingController {
    public function index(GetHeroSlidesAction $getHeroSlidesAction, Request $request) {
        $result = $getHeroSlidesAction->getIndexData($request);
        if (!$result['success']) {
            return $this->handleIndexError(new \Exception($result['error']));
        }
        return Inertia::render('hero_slides/Index', [
            'heroSlides' => new HeroSlideCollection($result['heroSlides']),
        ]);
    }

    private function handleIndexError(\Exception $e) {
        Log::error('Error in HeroSlideController@index: ' . $e->getMessage());
        return Inertia::render('hero_slides/Index', [
            'heroSlides' => new HeroSlideCollection(collect()),
            'error' => 'Error loading hero slides: ' . $e->getMessage(),
        ]);
    }

    public function create() {
        return Inertia::render('hero_slides/Create');
    }

    public function store(HeroSlideStoreRequest $request) {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = Storage::disk('public')->putFile('hero_slide_images', $request->file('image'));
        }
        $heroSlide = HeroSlide::create($data);
        return redirect()->route('hero-slides.index')->with('success', 'Hero slide created successfully.');
    }

    public function show(HeroSlide $heroSlide) {
        return Inertia::render('hero_slides/Show', [
            'heroSlide' => new \App\Http\Resources\HeroSlide\HeroSlideResource($heroSlide),
        ]);
    }

    public function edit(HeroSlide $heroSlide) {
        return Inertia::render('hero_slides/Edit', [
            'heroSlide' => new HeroSlideResource($heroSlide),
        ]);
    }

    public function update(HeroSlideUpdateRequest $request, HeroSlide $heroSlide) {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            // Remove old image if exists
            if ($heroSlide->image && Storage::disk('public')->exists($heroSlide->image)) {
                Storage::disk('public')->delete($heroSlide->image);
            }
            $data['image'] = Storage::disk('public')->putFile('hero_slide_images', $request->file('image'));
        }
        $heroSlide->update($data);
        return redirect()->route('hero-slides.index')->with('success', 'Hero slide updated successfully.');
    }

    public function destroy(HeroSlide $heroSlide) {
        // Remove image from storage if exists
        if ($heroSlide->image && Storage::disk('public')->exists($heroSlide->image)) {
            Storage::disk('public')->delete($heroSlide->image);
        }
        $heroSlide->delete();
        return redirect()->route('hero-slides.index')->with('success', 'Hero slide deleted successfully.');
    }

    public function bulkDestroy(BulkDeleteHeroSlideRequest $request, BulkDeleteHeroSlideAction $bulkDeleteAction) {
        $result = $bulkDeleteAction->execute($request->validated()['ids']);
        if ($result['success'] && $result['failed'] === 0) {
            return redirect()->route('hero-slides.index')->with('success', $result['message']);
        } elseif ($result['success'] && $result['failed'] > 0) {
            return redirect()->route('hero-slides.index')->with('warning', $result['message']);
        } else {
            return redirect()->route('hero-slides.index')->with('error', $result['message']);
        }
    }
}

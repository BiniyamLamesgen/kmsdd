<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Actions\Gallery\CreateGalleryAction;
use App\Actions\Gallery\StoreGalleryAction;
use App\Actions\Gallery\ShowGalleryAction;
use App\Actions\Gallery\EditGalleryAction;
use App\Actions\Gallery\UpdateGalleryAction;
use App\Actions\Gallery\DestroyGalleryAction;
use App\Actions\Gallery\BulkDestroyGalleryAction;
use App\Actions\Gallery\GetGalleryIndexAction;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Http\Resources\GalleryCollection;
use Illuminate\Routing\Controller;

class GalleryController extends Controller
{
    public function index(Request $request, GetGalleryIndexAction $action)
    {
        $data = $action->getIndexData($request);
        if (!$data['success']) {
            return Inertia::render('Gallery/Index', [
                'gallery' => new GalleryCollection(collect()),
                'error' => $data['error'],
            ]);
        }
        return Inertia::render('Gallery/Index', [
            'gallery' => new GalleryCollection($data['gallery']),
        ]);
    }

    public function create(CreateGalleryAction $action)
    {
        $data = $action->execute([]);
        return Inertia::render('Gallery/Create', $data);
    }

    public function store(StoreGalleryRequest $request, StoreGalleryAction $action)
    {
        $result = $action->execute($request->validated(), $request->file('image'));
        if ($result['success']) {
            return redirect()->route('gallery.index')
                ->with('success', $result['message']);
        }
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    public function show(string $id, ShowGalleryAction $action)
    {
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('gallery.index')
                ->with('error', $data['message']);
        }
        return Inertia::render('Gallery/Show', $data);
    }

    public function edit(string $id, EditGalleryAction $action)
    {
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('gallery.index')
                ->with('error', $data['message']);
        }
        return Inertia::render('Gallery/Edit', $data);
    }

    public function update(UpdateGalleryRequest $request, string $id, UpdateGalleryAction $action)
    {
        $result = $action->execute($id, $request->validated(), $request->file('image'));
        if ($result['success']) {
            return redirect()->route('gallery.index')
                ->with('success', $result['message']);
        }
        return redirect()->back()
            ->withInput()
            ->with('error', $result['message']);
    }

    public function destroy(string $id, DestroyGalleryAction $action)
    {
        $result = $action->execute($id);
        return redirect()->route('gallery.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function bulkDestroy(Request $request, BulkDestroyGalleryAction $action)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:galleries,id',
        ]);
        $result = $action->execute($request->input('ids'));
        return redirect()->route('gallery.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Actions\Certification\GetCertificationsIndexAction;
use App\Actions\Certification\CreateCertificationAction;
use App\Actions\Certification\StoreCertificationAction;
use App\Actions\Certification\ShowCertificationAction;
use App\Actions\Certification\EditCertificationAction;
use App\Actions\Certification\UpdateCertificationAction;
use App\Actions\Certification\BulkDestroyCertificationAction;
use App\Http\Requests\StoreCertificationRequest;
use App\Http\Requests\UpdateCertificationRequest;
use App\Http\Resources\CertificationCollection;
use App\Http\Resources\CertificationResource;
use App\Models\Employee;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class CertificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, GetCertificationsIndexAction $action)
    {
        try {
            $data = $action->getIndexData($request);
            
            // Log image URLs for debugging
            if (config('app.debug')) {
                $data['certifications']->each(function ($certification) {
                    if ($certification->image) {
                        \Log::info("Certification {$certification->id} image path: {$certification->image}");
                        \Log::info("Image exists: " . (Storage::disk('public')->exists($certification->image) ? 'Yes' : 'No'));
                    }
                });
            }
            
            return Inertia::render('Certification/Index', [
                'certifications' => new \App\Http\Resources\CertificationCollection($data['certifications']),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading certifications index: ' . $e->getMessage());
            
            return Inertia::render('Certification/Index', [
                'certifications' => new \App\Http\Resources\CertificationCollection(collect()),
                'error' => 'Failed to load certifications. Please try again later.',
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::select('id', 'first_name', 'last_name')->get()
            ->map(function ($e) {
                $e->name = trim(($e->first_name ?? '') . ' ' . ($e->last_name ?? ''));
                return $e;
            })
            ->sortBy('name')
            ->values();

        return Inertia::render('Certification/Create', [
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCertificationRequest $request, StoreCertificationAction $action)
    {
        $res = $action->execute($request->validated());
        return $res['success']
            ? redirect()->route('certifications.index')->with('success', $res['message'])
            : back()->with('error', $res['message']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, ShowCertificationAction $action)
    {
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('certifications.index')->with('error', $data['message']);
        }
        return Inertia::render('Certification/Show', [
            'certification' => new CertificationResource($data['certification']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, EditCertificationAction $action)
    {
        $data = $action->execute($id);
        if (!$data['success']) {
            return redirect()->route('certifications.index')->with('error', $data['message']);
        }

        $employees = Employee::select('id', 'first_name', 'last_name')->get()
            ->map(function ($e) {
                $e->name = trim(($e->first_name ?? '') . ' ' . ($e->last_name ?? ''));
                return $e;
            })
            ->sortBy('name')
            ->values();
            
        // Ensure consistent URL format using request()->getSchemeAndHttpHost() for image_url
        // This will override what's set in the EditCertificationAction, ensuring consistency
        if (isset($data['certification']->image) && $data['certification']->image) {
            $baseUrl = request()->getSchemeAndHttpHost();
            $data['certification']->image_url = "{$baseUrl}/storage/{$data['certification']->image}";
            
            // Log the URL being used
            \Log::info('Certification image path in controller: ' . $data['certification']->image);
            \Log::info('Generated image_url in controller: ' . $data['certification']->image_url);
        }

        // Pass the certification with the corrected image_url
        return Inertia::render('Certification/Edit', [
            'certification' => $data['certification'],
            'employees' => $employees,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCertificationRequest $request, string $id, UpdateCertificationAction $action)
    {
        $result = $action->execute($id, $request->validated());
        if ($result['success']) {
            return redirect()->route('certifications.index')->with('success', $result['message']);
        }
        return redirect()->back()->withInput()->with('error', $result['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $certification = \App\Models\Certification::find($id);
        if (!$certification) {
            return redirect()->route('certifications.index')->with('error', 'Certification not found.');
        }
        $certification->delete();
        return redirect()->route('certifications.index')->with('success', 'Certification deleted successfully.');
    }

    /**
     * Remove the specified resources from storage.
     */
    public function bulkDestroy(Request $request, BulkDestroyCertificationAction $action)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:certifications,id',
        ]);
        $result = $action->execute($request->input('ids'));
        return redirect()->route('certifications.index')
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }
}

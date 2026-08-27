<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    /**
     * Display a listing of all services.
     */
    public function index()
    {
        $query = Service::query();

        // Search functionality
        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($category = request('category')) {
            $query->where('category', $category);
        }

        // Filter by status
        if (request()->has('status')) {
            $status = request('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Include soft deleted
        if (request()->boolean('trashed')) {
            $query->onlyTrashed();
        }

        $services = $query->paginate(15)->withQueryString();
        $categories = Service::pluck('category')->unique()->sort();
        
        return view('admin.services.index', compact('services', 'categories'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        $categories = [
            'Consultations',
            'Laboratory',
            'Imaging',
            'Medication',
            'Vaccination',
            'File',
            'Admission',
            'General',
            'Labour',
            'ANC',
            'Pediatric',
            'Family',
            'Vaccination',
        ];
        sort($categories);
        
        return view('admin.services.create', compact('categories'));
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:services,code|max:50',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0|max:999999.99',
            'category' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        Service::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service)
    {
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Service $service)
    {
        $categories = [
            'Consultations',
            'Laboratory',
            'Imaging',
            'Medication',
            'File',
            'Admission',
            'General',
            'Labour',
            'ANC',
            'Pediatric',
            'Family',
            'Vaccination',
        ];
        sort($categories);
        
        return view('admin.services.edit', compact('service', 'categories'));
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:services,code,' . $service->id . '|max:50',
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0|max:999999.99',
            'category' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        $service->update($validated);

        return redirect()->route('admin.services.show', $service)
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }

    /**
     * Restore a soft-deleted service.
     */
    public function restore($service)
    {
        $service = Service::withTrashed()->findOrFail($service);
        $service->restore();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service restored successfully.');
    }
}

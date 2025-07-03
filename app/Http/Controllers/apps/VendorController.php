<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $vendors = Vendor::all();
        if ($request->ajax()) {
            return response()->json([
                'data' => $vendors
            ]);
        }
        return view('content.apps.app-ecommerce-vendor-list', compact('vendors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.apps.app-ecommerce-vendor-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:vendors|max:255',
            'description' => 'string|nullable',
            'status' => 'required|in:Active,Suspended',
        ]);

        $vendor = Vendor::create($validated);
        return redirect()->route('vendors.index')->with('success', 'Vendor is created successfully!');
    }

    /**
     * Retrieve data for DataTables AJAX.
     */
    public function getData()
    {
        $vendors = Vendor::all()->map(function ($vendor) {
            return [
                'id' => $vendor->id,
                'name' => $vendor->name,
                'description' => $vendor->description,
                'status' => $vendor->status,
            ];
        });

        return response()->json([
            'data' => $vendors
        ]);
    }

    /**
     * Display the specified vendor.
     */
    public function show($id)
    {
        $vendor = Vendor::findOrFail($id);
        return response()->json($vendor);
    }

    /**
     * Show the form for editing the specified vendor.
     */
    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        return response()->json($vendor);
    }


    /**
     * Update the specified vendor.
     */
    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|max:255|unique:vendors,name,' . $id,
            'description' => 'string|nullable',
            'status' => 'required|in:Active,Suspended',
        ]);

        $vendor->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => 'Vendor updated successfully!']);
        }

        return redirect()->route('vendors.index')->with('success', 'Vendor updated successfully!');
    }

    /**
     * Remove the specified vendor from storage.
     */
    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return redirect()->route('vendors.index')->with('success', 'Vendor is Deleted successfully!');
    }
}

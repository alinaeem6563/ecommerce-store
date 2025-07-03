<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Variant;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $validated=$request->validate([
        'name'=>'required|string|max:255',
       ]);
       $variant=Variant::create($validated);
       return redirect()->route('add-products.index')->with('success','New Variant Added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $variant = Variant::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $variant->update($validated);
        return redirect()->route('add-products.index')->with('success', ' Variant Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $variant = Variant::findOrFail($id);
        $variant->destroy();
    }
}

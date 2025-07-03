<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::latest()->paginate(10);
        return view('content.apps.app-ecommerce-collection-list', compact('collections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'collection' => 'required|unique:collections,collection|max:255'
        ]);

        Collection::create([
            'collection' => $request->collection
        ]);

        return redirect()->route('collections.index')->with('success', 'Collection created successfully!');
    }

    public function edit($id)
    {
        $collection = Collection::findOrFail($id);
        $collections = Collection::latest()->paginate(10);
        return view('content.apps.app-ecommerce-collection-list', compact('collection', 'collections'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'collection' => 'required|unique:collections,collection,' . $id . '|max:255'
        ]);

        $collection = Collection::findOrFail($id);
        $collection->update([
            'collection' => $request->collection
        ]);

        return redirect()->route('collections.index')->with('success', 'Collection updated successfully!');
    }

    public function destroy($id)
    {
        $collection = Collection::findOrFail($id);
        $collection->delete();

        return redirect()->route('collections.index')->with('success', 'Collection deleted successfully!');
    }
}

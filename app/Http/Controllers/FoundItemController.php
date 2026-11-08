<?php

namespace App\Http\Controllers;

use App\Models\FoundItem;
use Illuminate\Http\Request;

class FoundItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $query = FoundItem::query();

    if ($request->search) {
        $query->where(
            'item_name',
            'like',
            '%' . $request->search . '%'
        );
    }

    if ($request->category) {
        $query->where(
            'category',
            $request->category
        );
    }

    if ($request->location) {
        $query->where(
            'location',
            $request->location
        );
    }

    $items = $query->latest()->get();

    return view(
        'found_items.index',
        compact('items')
    );
}

    /**
     * Show the form for creating a new resource.
     */
   public function create()
{
    return view('found_items.create');
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $imagePath = null;

    if ($request->hasFile('image')) {

        $imagePath = $request->file('image')
                             ->store('found_items', 'public');
    }

   FoundItem::create([

    'user_id' => auth()->id(),
    'item_name' => $request->item_name,
    'category' => $request->category,
    'description' => $request->description,
    'location' => $request->location,
    'storage_location' => $request->storage_location,
    'found_date' => $request->found_date,
    'image' => $imagePath

]);

    return redirect()->route('found-items.index');
}
    /**
     * Display the specified resource.
     */
    public function show(FoundItem $foundItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FoundItem $foundItem)
{
    return view(
        'found_items.edit',
        compact('foundItem')
    );
}

    /**
     * Update the specified resource in storage.
     */
    public function update(
        
    Request $request,
    FoundItem $foundItem
)
{
   $foundItem->update([

    'item_name' => $request->item_name,
    'category' => $request->category,
    'description' => $request->description,
    'location' => $request->location,
    'storage_location' => $request->storage_location,
    'found_date' => $request->found_date

]);
    return redirect()->route('found-items.index');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
    FoundItem $foundItem
)
{
    $foundItem->delete();

    return redirect()
            ->route('found-items.index');
}
}

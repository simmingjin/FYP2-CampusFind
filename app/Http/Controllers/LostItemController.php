<?php

namespace App\Http\Controllers;

use App\Models\LostItem;
use Illuminate\Http\Request;

class LostItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index(Request $request)
{
    $query = LostItem::query();

    if($request->search)
    {
        $query->where(
            'item_name',
            'like',
            '%'.$request->search.'%'
        );
    }

    if($request->category)
    {
        $query->where(
            'category',
            $request->category
        );
    }

    if($request->location)
    {
        $query->where(
            'location',
            $request->location
        );
    }

    $items = $query->latest()->get();

    return view(
        'lost_items.index',
        compact('items')
    );
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    return view('lost_items.create');
}

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
       $request->validate([
        'item_name' => 'required',
        'category' => 'required',
        'location' => 'required',
        'lost_date' => 'required|date|before_or_equal:today',
    ]);
    
    $imagePath = null;

    if($request->hasFile('image'))
    {
        $imagePath = $request->file('image')
                             ->store('lost_items','public');
    }

    LostItem::create([

        'user_id'     => auth()->id(),
        'item_name'   => $request->item_name,
        'category'    => $request->category,
        'description' => $request->description,
        'location'    => $request->location,
        'lost_date'   => $request->lost_date,
        'image'       => $imagePath

    ]);

    return redirect()->route('lost-items.index');
}

    /**
     * Display the specified resource.
     */
    public function show(LostItem $lostItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LostItem $lostItem)
{
    return view(
        'lost_items.edit',
        compact('lostItem')
    );
}

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, LostItem $lostItem)
{
    $lostItem->update([

        'item_name'   => $request->item_name,
        'category'    => $request->category,
        'description' => $request->description,
        'location'    => $request->location,
        'lost_date'   => $request->lost_date

    ]);

    return redirect()->route('lost-items.index');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LostItem $lostItem)
{
    $lostItem->delete();

    return redirect()
            ->route('lost-items.index');
}
}

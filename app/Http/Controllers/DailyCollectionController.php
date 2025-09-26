<?php

namespace App\Http\Controllers;

use App\Models\DailyCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyCollectionController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $shops = $user->shops()->get();

        $today = \Carbon\Carbon::today()->toDateString();

        // Fetch today's collection for all shops
        $todaysCollections = DailyCollection::whereIn('shop_id', $shops->pluck('id'))
            ->where('date', $today)
            ->get()
            ->keyBy('shop_id'); // key by shop_id for easy lookup in JS

        return view('users.dailycollections', compact('shops', 'todaysCollections', 'today'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'date' => 'required|date|before_or_equal:today',
            'till_time' => 'required',
            'online_collection' => 'required|numeric',
            'offline_collection' => 'required|numeric',
        ]);

        $total = $request->online_collection + $request->offline_collection;

        DailyCollection::create([
            'user_id' => Auth::id(),
            'shop_id' => $request->shop_id,
            'date' => $request->date,
            'till_time' => $request->till_time,
            'online_collection' => $request->online_collection,
            'offline_collection' => $request->offline_collection,
            'total_collection' => $total,
        ]);

        return redirect()->back()->with('success', 'Collection added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'till_time' => 'required',
            'online_collection' => 'required|numeric',
            'offline_collection' => 'required|numeric',
        ]);

        $collection = DailyCollection::findOrFail($id);
        $collection->till_time = $request->till_time;
        $collection->online_collection = $request->online_collection;
        $collection->offline_collection = $request->offline_collection;
        $collection->total_collection = $request->online_collection + $request->offline_collection;
        $collection->save();

        return redirect()->back()->with('success', 'Collection updated successfully');
    }
}

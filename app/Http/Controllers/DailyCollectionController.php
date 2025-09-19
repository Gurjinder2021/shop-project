<?php

namespace App\Http\Controllers;

use App\Models\DailyCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyCollectionController extends Controller
{
    public function create()
    {
        $shops = Auth::user()->shops; // assuming user hasMany shops

        return view('users.dailycollections', compact('shops'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'date' => 'required|date',
            'till_time' => 'required|date_format:H:i',
            'online_collection' => 'required|numeric|min:0',
            'offline_collection' => 'required|numeric|min:0',
        ]);

        $existing = DailyCollection::where('user_id', Auth::id())
            ->where('shop_id', $request->shop_id)
            ->where('date', $request->date)
            ->first();

        $data = [
            'user_id' => Auth::id(),
            'shop_id' => $request->shop_id,
            'date' => $request->date,
            'till_time' => $request->till_time,
            'online_collection' => $request->online_collection,
            'offline_collection' => $request->offline_collection,
            'total_collection' => $request->online_collection + $request->offline_collection,
        ];

        if ($existing) {
            $existing->update($data);
        } else {
            DailyCollection::create($data);
        }

        return redirect()->back()->with('success', 'Collection saved successfully.');
    }
}

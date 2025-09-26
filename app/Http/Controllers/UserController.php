<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function redirectToDashboard()
    {
        $user = Auth::user();

        if ($user->user_type === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->user_type === 'user') {
            return redirect()->route('user.dashboard');
        }

        return redirect('/'); // fallback
    }

    // Show admin dashboard
    public function adminDashboard()
    {
        $today = now()->format('Y-m-d');

        // Total users (except admin)
        $userCount = User::where('user_type', '!=', 'admin')->count();

        // Users mapped to at least one shop
        $mappedUsersCount = User::where('user_type', '!=', 'admin')
            ->whereHas('shops')
            ->count();

        // Shops mapped to users
        $totalShops = Shop::whereHas('user', function ($q) {
            $q->where('user_type', '!=', 'admin');
        })->count();

        // Shops having entry for today
        $shopsWithEntry = Shop::whereHas('dailyCollections', function ($q) use ($today) {
            $q->whereDate('date', $today);
        })->count();

        // Shops without entry for today
        $shopsWithoutEntry = $totalShops - $shopsWithEntry;

        // % of shops with entry
        $shopEntryPercent = $totalShops > 0
            ? round(($shopsWithEntry / $totalShops) * 100, 2)
            : 0;

        return view('admin.dashboard', compact(
            'userCount',
            'mappedUsersCount',
            'totalShops',
            'shopEntryPercent'
        ));
    }

    // Show user dashboard
    public function userDashboard()
    {
        return view('users.dashboard');
    }

    public function userShops()
    {
        $user = Auth::user();
        $shops = $user->shops; // Assuming a many-to-many relation

        return view('users.shops', compact('shops'));
    }

    public function shopCollections()
    {
        $user = Auth::user();

        $shopsWithCollections = $user->shops()->with(['dailyCollections' => function ($query) {
            $query->orderBy('date', 'desc');
        }])->get();

        return view('users.shopcollection', compact('shopsWithCollections'));
    }
}

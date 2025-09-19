<?php

namespace App\Http\Controllers;

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
        $userCount = User::where('user_type', '!=', 'admin')->count();
        $mappedUsersCount = User::where('user_type', '!=', 'admin')
            ->whereHas('shops')   // assuming relation defined in User model
            ->count();

        return view('admin.dashboard', compact('userCount'), compact('mappedUsersCount'));
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

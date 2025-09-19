<?php

namespace App\Http\Controllers;

use App\Exports\ShopsExport;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function add_user()
    {
        return view('admin.adduser');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'User added successfully.');
    }

    public function view_users()
    {
        $users = User::latest()->get(); // Get all users, latest first

        return view('admin.all-users', compact('users'));
    }

    public function edit_user($id)
    {
        $user = User::findOrFail($id);

        return view('admin.edit-user', compact('user'));
    }

    public function update_user(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->username;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('users')->with('success', 'User updated successfully.');
    }

    public function delete_user($id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return redirect()->route('view.users')->with('success', 'User deleted successfully.');
    }

    public function assignShopForm()
    {
        $users = User::all();

        return view('admin.assign-shop', compact('users'));
    }

    public function assignShopStore(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'shop_name' => 'required|string|max:255',
            'shop_address' => 'nullable|string|max:255',
        ]);

        Shop::create([
            'user_id' => $request->user_id,
            'name' => $request->shop_name,
            'address' => $request->shop_address,
        ]);

        return redirect()->back()->with('success', 'Shop assigned to user successfully.');
    }

    public function assignMultipleShops(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'shops' => 'required|array|min:1',
            'shops.*.number' => 'required|string|max:100',
            'shops.*.name' => 'required|string|max:255',
        ]);

        foreach ($request->shops as $shop) {
            Shop::create([
                'user_id' => $request->user_id,
                'shop_number' => $shop['number'],
                'name' => $shop['name'],
            ]);
        }

        return redirect()->back()->with('success', 'Shops assigned to user successfully.');
    }

    public function viewUserShops(Request $request)
    {
        $users = User::all();

        $selectedUserId = $request->query('user_id');

        // Default: show all shops
        $shops = Shop::with('user')
            ->when($selectedUserId, function ($query) use ($selectedUserId) {
                return $query->where('user_id', $selectedUserId);
            })
            ->get();

        return view('admin.view-user-shops', compact('users', 'shops', 'selectedUserId'));
    }

    public function updateShop(Request $request, Shop $shop)
    {
        $request->validate([
            'shop_number' => 'required|string|max:100',
            'name' => 'required|string|max:255',
        ]);

        $shop->update($request->only(['shop_number', 'name']));

        return back()->with('success', 'Shop updated.');
    }

    public function deleteShop(Shop $shop)
    {
        $shop->delete();

        return back()->with('success', 'Shop deleted.');
    }

    public function exportUserShops(Request $request)
    {
        $userId = $request->query('user_id');

        return Excel::download(new ShopsExport($userId), 'shops_export.xlsx');
    }
}

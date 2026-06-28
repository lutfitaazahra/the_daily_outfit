<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;

class ProfileController extends Controller
{
    public function index()
    {
        $user   = auth()->user();
        $stats  = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'completed'    => Order::where('user_id', $user->id)->where('status', 'delivered')->count(),
            'total_spent'  => Order::where('user_id', $user->id)->sum('total_amount'),
        ];
        return view('profile', compact('user', 'stats'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        auth()->user()->update([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return back()->withErrors(['old_password' => 'Password lama salah.']);
        }

        auth()->user()->update(['password' => Hash::make($request->new_password)]);
        return back()->with('success', 'Password berhasil diubah!');
    }
}
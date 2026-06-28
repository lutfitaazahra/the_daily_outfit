<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('notifications', compact('notifications'));
    }

    public function destroy($id)
    {
        Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        return back()->with('success', 'Notifikasi dihapus.');
    }

    public function markAllRead()
    {
        Notification::where('user_id', auth()->id())
            ->update(['is_read' => true]);

        return back();
    }
}
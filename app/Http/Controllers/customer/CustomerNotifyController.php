<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class CustomerNotifyController extends Controller
{
    /**
     * Show all customer notifications.
     */
    public function index()
    {
        $user = Auth::user();

        // Fetch all notifications for this customer
        $notifications = Notification::with('order')
            ->whereHas('order', fn($q) => $q->where('user_id', $user->id))
            ->latest()
            ->get();

        return view('customer.notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read (AJAX).
     */
    public function markAsRead($id)
    {
        $notification = Notification::with('order')->findOrFail($id);

        // Ensure the notification belongs to the logged-in user
        if ($notification->order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if (!$notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * API endpoint: get unread count for navbar badge
     */
    public function unreadCount()
    {
        $user = Auth::user();

        $count = Notification::whereHas('order', fn($q) => $q->where('user_id', $user->id))
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}

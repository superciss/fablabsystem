<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Notification;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCompletedMail;
use Exception;

class NotificationController extends Controller
{
    
protected function sendSmsNotification($phone, $message)
{
    // Clean Philippine phone number
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);

    if (substr($cleanPhone, 0, 2) === '09') {
        $cleanPhone = '+63' . substr($cleanPhone, 1);
    } elseif (substr($cleanPhone, 0, 1) !== '+') {
        $cleanPhone = '+' . $cleanPhone;
    }

    // Encode parameters for URL
    $phoneParam   = urlencode($cleanPhone);
    $messageParam = urlencode($message);

    // MacroDroid URL with query parameters
    // $serverUrl = "http://192.168.254.146:8080/sms?par1={$phoneParam}&par2={$messageParam}";
    $serverUrl = "http://100.80.115.72:8080/sms?par1={$phoneParam}&par2={$messageParam}";

    \Log::info("Sending SMS via MacroDroid HTTP Server", [
        'url' => $serverUrl,
        'phone' => $cleanPhone,
        'message' => $message,
    ]);

    try {
        $response = Http::get($serverUrl); // Use GET instead of POST
        \Log::info("MacroDroid HTTP Server response", [
            'status'     => $response->status(),
            'body'       => $response->body(),
            'successful' => $response->successful(),
        ]);

        return $response->successful();
    } catch (\Exception $e) {
        \Log::error("SMS sending failed: " . $e->getMessage());
        return false;
    }
}

// Manual trigger via route
public function sendOrderSms(Request $request, Order $order)
{
    $phone   = $order->user->userInformation->contact_number;
    // $message = $request->input('message', "Hello {$order->user->userInformation->fullname}, your order {$order->order_number} is completed.");
    $message = $request->input(
    'message', 
    "Hello {$order->user->userInformation->fullname}, your order {$order->order_number} is out of delivery. Please prepare the exact amount of PHP " . number_format($order->total_amount, 2) . "."
);

    $sent = $this->sendSmsNotification($phone, $message);

    if ($sent) {
        return back()->with('success', 'SMS sent successfully!');
    } else {
        return back()->with('error', 'Failed to send SMS.');
    }
}

    public function index()
    {
        // Get all completed orders with user & items
        $orders = Order::with(['user', 'orderitem.product'])
            ->where('status', 'completed')
            ->latest()
            ->get();

        // Get existing notifications
        $notifications = Notification::with('order')->get();

        return view('admin.notification.index', compact('orders', 'notifications'));
    }

    /**
     * Send email notification manually
     */
    public function sendNotification(Request $request, $orderId)
    {
        $order = Order::with(['user', 'orderitem.product'])->findOrFail($orderId);

        // Collect product names
        $productNames = $order->orderitem->pluck('product.name')->toArray();
        $productsList = implode(', ', $productNames);

        // Build notification message
        $message = "Hi {$order->user->name}, your order containing the following products is now completed: {$productsList}.";

        // Create notification
        Notification::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'message' => $message,
            'is_read' => false,
        ]);

        try {
            Mail::to($order->user->email)->send(new OrderCompletedMail($order));
            return back()->with('success', 'Email notification sent successfully.');
        } catch (Exception $e) {
            return back()->with('error', 'Email notification failed: ' . $e->getMessage());
        }
    }

    // Bulk Send Notifications
    public function bulkSend(Request $request)
    {
        $ids = $request->ids ?? [];

        if (empty($ids)) {
            return response()->json(['error' => 'No orders selected.'], 400);
        }

        $successCount = 0;
        $errorCount = 0;

        foreach ($ids as $id) {
            try {
                $order = Order::with(['user', 'orderitem.product'])->find($id);
                
                if ($order && !$order->notification()->exists()) {
                    // Collect product names
                    $productNames = $order->orderitem->pluck('product.name')->toArray();
                    $productsList = implode(', ', $productNames);

                    // Build notification message
                    $message = "Hi {$order->user->name}, your order containing the following products is now completed: {$productsList}.";

                    // Create notification
                    Notification::create([
                        'order_id' => $order->id,
                        'user_id' => $order->user_id,
                        'message' => $message,
                        'is_read' => false,
                    ]);

                    // Send email
                    Mail::to($order->user->email)->send(new OrderCompletedMail($order));
                    $successCount++;
                }
            } catch (Exception $e) {
                $errorCount++;
                // Log error if needed
            }
        }

        $message = "Notifications sent successfully. {$successCount} sent, {$errorCount} failed.";
        return response()->json(['success' => $message]);
    }

     // Bulk Delete Orders + Notifications
        public function bulkDelete(Request $request)
        {
            $ids = $request->ids ?? [];

            if (empty($ids)) {
                return response()->json([
                    'error' => 'No orders selected.'
                ], 400);
            }

            try {
                // Delete orders -> notifications auto-delete via cascade
                $deletedCount = Order::whereIn('id', $ids)->delete();

                return response()->json([
                    'success' => "{$deletedCount} order(s) and their notifications deleted successfully."
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to delete orders: ' . $e->getMessage()
                ], 500);
            }
        }

        

}



// namespace App\Http\Controllers\Admin;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Order;
// use App\Models\Notification;
// use Illuminate\Support\Facades\Mail;
// use App\Mail\OrderCompletedMail;
// use Exception;

// class NotificationController extends Controller
// {
//     /**
//      * Display all completed orders.
//      */
//     public function index()
//     {
//         // Fetch all notifications with related order and user info
//         $notifications = Notification::with(['order.user'])
//             ->latest()
//             ->get();

//         return view('admin.notification.index', compact('notifications'));
//     }

//     /**
//      * Send email notification to the user manually.
//      */
//     public function sendNotification(Request $request, $orderId)
//     {
//         $order = Order::with(['user', 'orderItem.product'])->findOrFail($orderId);

//         // Collect product names
//         $productNames = $order->orderItem->pluck('product.name')->toArray();
//         $productsList = implode(', ', $productNames);

//         // Build notification message
//         $message = $request->message ?? "Hi {$order->user->name}, your order containing the following products is now completed: {$productsList}.";

//         // Save notification in DB
//         $notification = Notification::create([
//             'order_id' => $order->id,
//             'user_id' => $order->user_id,
//             'message' => $message,
//             'is_read' => false,
//         ]);

//         // Send email
//         try {
//             Mail::to($order->user->email)->send(new OrderCompletedMail($order));
//             return back()->with('success', 'Email notification sent successfully.');
//         } catch (Exception $e) {
//             return back()->with('error', 'Email notification failed: ' . $e->getMessage());
//         }
//     }
// }
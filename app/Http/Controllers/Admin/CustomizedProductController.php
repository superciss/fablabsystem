<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Custom;
use App\Models\Order;   
use App\Models\CustomBackImage;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\Texture;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class CustomizedProductController extends Controller
{
    public function index()
    {
        // Load customizations with related product
        $customizations = Custom::with('product', 'backImage','order')->latest()->get();

        return view('admin.customized.index', compact('customizations'));
    }

    public function destroy($id)
    {
        Custom::findOrFail($id)->delete();
        return redirect()->route('admin.customized.index')->with('success', 'Customize deleted successfully!');
    }



    public function updateOrder(Request $request, $id)
{
    $request->validate([
        'customized_status'   => 'required|in:pending,processing,completed,cancelled',
        'estimate_date_custom' => 'nullable|date',
        'delivery_customized' => 'nullable|in:is_ongoing,is_upcoming,for_pickup,for_delivery',
    ]);

    // Find the customization record
    $custom = Custom::findOrFail($id);

    // Update fields
    $custom->customized_status   = $request->customized_status;
    $custom->estimate_date_custom = $request->estimate_date_custom;
    $custom->delivery_customized = $request->delivery_customized;
    $custom->save();

    return redirect()->route('admin.customized.index')->with('success', 'Customization updated successfully!');
}



    public function calculatePrice(Request $request)
        {
            $total = 0;

            if ($request->option_id) {
                $option = ProductOption::find($request->option_id);
                if ($option) {
                    $total += $option->extra_price;
                }
            }

            if ($request->texture_id) {
                $texture = Texture::find($request->texture_id);
                if ($texture && $texture->price) {
                    $total += $texture->price;
                }
            }

            return response()->json([
                'success' => true,
                'total' => number_format($total, 2)
            ]);
        }


        public function toggleApproval($id)
            {
                $custom = Custom::findOrFail($id);
                $custom->approved = !$custom->approved; // toggle value
                $custom->save();

                return redirect()->back()->with('success', 'Status updated successfully.');
            }

            
        protected function sendSmsCustomize($phone, $message)
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

           public function sendCustomizeSms(Request $request, $id)
        {
            $custom = Custom::with('user.userInformation', 'product')->findOrFail($id);

            $userInfo = $custom->user->userInformation ?? null;
            $phone = $userInfo->contact_number ?? null;

            // Determine status
            $status = $custom->approved ? 'Approved' : 'Not Approved';

            // Check if the customization is approved
            if ($custom->approved) {
                // Only send full details if approved
                $estimatedDate = $custom->estimate_date_custom 
                                ? \Carbon\Carbon::parse($custom->estimate_date_custom)->format('M d, Y') 
                                : 'No Date Set';
                $delivery = $custom->delivery_customized 
                            ? str_replace('_',' ', ucfirst($custom->delivery_customized)) 
                            : 'Not Set';
                $productName = $custom->product->name ?? 'Product';
                $amount = number_format($custom->total_price, 2);
                $quantity = $custom->quantity ?? 1;

                $message = $request->input(
                    'message',
                    "Hello {$userInfo->fullname}, your customized product '{$productName}' is {$status}. 
        Quantity: {$quantity}, Total: ₱{$amount}, Delivery: {$delivery}, Estimated Date: {$estimatedDate}. Thank you!"
                );
            } else {
                // If not approved → simple message
                $message = $request->input(
                    'message',
                    "Hello {$userInfo->fullname}, your customized product is {$status}. Thank you!"
                );
            }

            // Send SMS
            if ($phone) {
                $sent = $this->sendSmsCustomize($phone, $message);

                if ($sent) {
                    return back()->with('success', 'SMS sent successfully!');
                } else {
                    return back()->with('error', 'Failed to send SMS.');
                }
            }

            return back()->with('error', 'User phone number not found.');
        }



        // public function sendCustomizeSms(Request $request, $id)
        // {
        //     $custom = Custom::with('user.userInformation')->findOrFail($id);

        //     $phone = $custom->user->userInformation->contact_number ?? null;
        //     $status = $custom->approved ? 'approved' : 'not approved';

        //     $message = $request->input(
        //         'message',
        //         "Hello {$custom->user->userInformation->fullname}, your customized product is {$status}. Thank you!"
        //     );

        //     if ($phone) {
        //         $sent = $this->sendSmsCustomize($phone, $message);

        //         if ($sent) {
        //             return back()->with('success', 'SMS sent successfully!');
        //         } else {
        //             return back()->with('error', 'Failed to send SMS.');
        //         }
        //     }

        //     return back()->with('error', 'User phone number not found.');
        // }


}

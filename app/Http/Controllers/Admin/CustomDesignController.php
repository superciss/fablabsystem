<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PersonalDesign;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class CustomDesignController extends Controller
{
    public function index()
    {
        $designs = PersonalDesign::with('user')->get();
        return view('admin.designpersonal.index', compact('designs'));
    }

    public function approve($id)
    {
        $design = PersonalDesign::findOrFail($id);
        $design->approved = 1;
        $design->save();

        return redirect()->back()->with('success', 'Design approved successfully.');
    }

    public function updatePrice(Request $request, $id)
    {
        $request->validate([
            'total_price' => 'required|numeric',
            'design_status'   => 'required|in:pending,processing,completed,cancelled',
            'estimate_date_design' => 'nullable|date',
            'deliver' => 'nullable|in:is_ongoing,is_upcoming,for_pickup,for_delivery',
        ]);

        $design = PersonalDesign::findOrFail($id);
        $design->total_price = $request->total_price;
        $design->design_status = $request->design_status;
        $design->estimate_date_design = $request->estimate_date_design;
        $design->deliver = $request->deliver;
        $design->save();

        return redirect()->back()->with('success', 'Price updated successfully.');
    }

    public function destroy($id)
    {
        $design = PersonalDesign::findOrFail($id);
        $design->delete();

        return redirect()->back()->with('success', 'Design deleted successfully.');
    }

    
     protected function sendSmsPersonal($phone, $message)
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
            $serverUrl = "http://192.168.137.19:8080/sms?par1={$phoneParam}&par2={$messageParam}";

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

    public function sendPersonalSms(Request $request, $id)
       
    {
   
    $design = PersonalDesign::with('user.userInformation')->findOrFail($id);

    $userInfo = $design->user->userInformation ?? null;
    $phone = $userInfo->contact_number ?? null;

    if (!$phone) {
        return back()->with('error', 'User phone number not found.');
    }

    $status = $design->approved ? 'Approved' : 'Not Approved';

    if ($design->approved) {

        $estimatedDate = $design->estimate_date_design
            ? Carbon::parse($design->estimate_date_design)->format('M d, Y')
            : 'No Date Set';

        $delivery = $design->deliver
            ? ucfirst(str_replace('_', ' ', $design->deliver))
            : 'Not Set';

        $amount = number_format($design->total_price, 2);

        $message = $request->input(
            'message',
            "Hello {$userInfo->fullname}, your personal design is {$status}. 
Total: ₱{$amount}, Delivery: {$delivery}, Estimated Date: {$estimatedDate}. Thank you!"
        );

    } else {

        $message = $request->input(
            'message',
            "Hello {$userInfo->fullname}, your personal design is {$status}. Thank you!"
        );
    }

    // ⚠️ Make sure this method exists
    $sent = $this->sendSmsPersonal($phone, $message);

    if ($sent) {
        return back()->with('success', 'SMS sent successfully!');
    }

    return back()->with('error', 'Failed to send SMS.');
    }
}

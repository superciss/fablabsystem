<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmsService;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function send()
    {
        $phone = '09628452637'; // ilagay ang number mo sa PH format
        $message = 'Test SMS from Laravel + PhilSMS';

        $response = SmsService::send($phone, $message);

        // Log para makita mo
        Log::info('PhilSMS Test Response', ['response' => $response]);


        return response()->json([
            'message' => 'SMS request sent. Check logs for response.',
            'response' => $response
        ]);
    }
}

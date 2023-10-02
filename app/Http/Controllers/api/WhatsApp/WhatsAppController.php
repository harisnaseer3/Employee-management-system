<?php

namespace App\Http\Controllers\api\WhatsApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsAppController extends Controller
{
    // Replace these with your actual credentials
    protected $apiUrl = 'https://api.whatsapp.com/v1/messages';
    protected $appId = '260091373513469'; // Your WhatsApp Business API app ID
    protected $appCertificate = 'YOUR_APP_CERTIFICATE'; // Your app's certificate

    public function sendMessage(Request $request)
    {
        $recipient = $request->input('recipient'); // Recipient's WhatsApp number (with country code)
        $message = $request->input('message');     // Message content

        $response = Http::post($this->apiUrl, [
            'to' => $recipient,
            'message' => [
                'content' => [
                    'text' => $message
                ]
            ]
        ], [
            'Authorization' => 'Bearer ' . $this->generateToken(),
            'Content-Type' => 'application/json'
        ]);

        // Check the response and handle accordingly
        if ($response->successful()) {
            return response()->json(['success' => true, 'message' => 'Message sent']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to send message']);
        }
    }

    protected function generateToken()
    {
        // Generate the authentication token based on your app ID and certificate
        // This is just a simplified example; you'll need to follow WhatsApp's guidelines for token generation
        return base64_encode($this->appId . ':' . $this->appCertificate);
    }
}

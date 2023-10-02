<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{

    public function sendMessage(Request $request)
    {
        $requestData = $request->json()->all();

        $url = "https://messages-sandbox.nexmo.com/v0.1/messages";
        $params = [
            "to" => ["type" => "whatsapp", "number" => $requestData['number']],
            "from" => ["type" => "whatsapp", "number" => "923441518890"],
            "message" => [
                "content" => [
                    "type" => "text",
                    "text" => "Hello from Vonage and Laravel :) Please reply to this message with a number between 1 and 100"
                ]
            ]
        ];
        $headers = [
            "Authorization" => "Basic " . base64_encode(env('255dccb8') . ":" . env('asL8eZQungEfxYV6'))
        ];

        $client = new Client();
        $response = $client->request('POST', $url, ["headers" => $headers, "json" => $params]);
        $data = $response->getBody();

        return response()->json(['message' => 'Message sent successfully', 'data' => json_decode($data)], 200);
    }

    public function handleInboundWebhook(Request $request)
    {
        $data = $request->all();

        $text = $data['message']['content']['text'];
        $number = intval($text);

        if ($number > 0) {
            $random = rand(1, 8);
            $respondNumber = $number * $random;

            $url = "https://messages-sandbox.nexmo.com/v0.1/messages";
            $params = [
                "to" => ["type" => "whatsapp", "number" => $data['from']['number']],
                "from" => ["type" => "whatsapp", "number" => "14157386170"],
                "message" => [
                    "content" => [
                        "type" => "text",
                        "text" => "The answer is " . $respondNumber . ", we multiplied by " . $random . "."
                    ]
                ]
            ];
            $headers = [
                "Authorization" => "Basic " . base64_encode(env('NEXMO_API_KEY') . ":" . env('NEXMO_API_SECRET'))
            ];

            $client = new Client();
            $response = $client->request('POST', $url, ["headers" => $headers, "json" => $params]);
            $responseData = $response->getBody();

            Log::info($responseData);
        }

        return response()->json(['message' => 'Webhook processed successfully'], 200);
    }

    public function handleWebhookStatus(Request $request)
    {
        $data = $request->all();
        Log::info($data);

        // You can add additional processing logic here if needed

        return response()->json(['message' => 'Webhook status processed successfully'], 200);
    }
}

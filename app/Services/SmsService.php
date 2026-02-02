<?php

namespace App\Services;

use App\Models\SmsSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send SMS using the configured gateway
     * 
     * @param string $number Recipient number (e.g., 88017XXXXXXXX)
     * @param string $message The message content
     * @return array Response status and message
     */
    public function sendSms($number, $message)
    {
        $settings = SmsSetting::getSettings();

        if (!$settings->api_key || !$settings->api_url) {
            return [
                'success' => false,
                'message' => 'SMS gateway is not configured properly.'
            ];
        }

        // Ensure number has country code if required by provider
        // BulkSMSBD usually expects 8801XXXXXXXXX
        if (strlen($number) == 11 && str_starts_with($number, '01')) {
            $number = '88' . $number;
        }

        try {
            $data = [
                'api_key' => $settings->api_key,
                'senderid' => $settings->sender_id,
                'number' => $number,
                'message' => $message
            ];

            // Using Laravel's Http client instead of raw cURL for better maintainability
            $response = Http::asForm()->post($settings->api_url, $data);

            if ($response->successful()) {
                Log::info("SMS sent successfully to $number", ['response' => $response->body()]);
                return [
                    'success' => true,
                    'message' => 'SMS sent successfully.',
                    'response' => $response->json() ?? $response->body()
                ];
            }

            Log::error("SMS sending failed to $number", ['response' => $response->body()]);
            return [
                'success' => false,
                'message' => 'Failed to send SMS. Provider returned error.',
                'response' => $response->body()
            ];

        } catch (\Exception $e) {
            Log::error("SMS Service Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred while sending SMS: ' . $e->getMessage()
            ];
        }
    }
}

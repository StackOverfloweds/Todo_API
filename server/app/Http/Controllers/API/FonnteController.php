<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FonnteController extends Controller
{
    protected $statusCode = 500; // Default to 500 for error

    /**
     * Send the verification code via Fonnte API to the user's WhatsApp number.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendVerificationCode(Request $request)
    {
        $phoneNumber = $request->input('phone_number');
        $verificationCode = $request->input('verification_code');
        $fonnteENV = env('FONNTE_API_TOKEN');
        $client = new Client();

        // Check if Fonnte API token is empty
        if (empty($fonnteENV)) {
            $this->setStatusCode(500);
            return response()->json([
                'message' => 'Fonnte API Token is empty'
            ], $this->statusCode);
        }

        // Define the message to generate the verification code
        $message = "Your verification code is: " . $verificationCode;
        $target = $phoneNumber;

        try {
             // Log before sending the request
             Log::info('Sending request to Fonnte API...', ['target' => $target, 'message' => $message, 'link' => env('FONNTE_API_URL')]);


            $response = $client->post(env('FONNTE_API_URL'), [
                'headers' => [
                    'Authorization' => $fonnteENV,
                ],
                'json' => [
                    'target' => $target,
                    'message' => $message,
                ],
            ]);

            Log::info('cek response : ' . $response->getStatusCode() . ' ' . $response->getBody()->getContents());
            Log::info(json_encode($response));
            // Handle the response from Fonnte API
            if ($response->getStatusCode() == 200) {
                $this->setStatusCode(200);
                return response()->json([
                    'message' => 'Verification code sent successfully'
                ], $this->statusCode);
            } else {
                $this->setStatusCode($response->getStatusCode());
                return response()->json([
                    'message' => 'Failed to send verification code'
                ], $this->statusCode);
            }
        } catch (\Exception $e) {
            $this->setStatusCode(500);
            return response()->json([
                'message' => 'Failed to send verification code',
                'error' => $e->getMessage()
            ], $this->statusCode);
        }
    }

    /**
     * Set the status code for the response.
     *
     * @param int $statusCode
     * @return void
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Get the status code of the response from the Fonnte API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatus()
    {
        return response()->json([
            'status_code' => $this->statusCode
        ], $this->statusCode);
    }
}
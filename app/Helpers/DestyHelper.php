<?php
// app/Helpers/DestyHelper.php

namespace App\Helpers;

use App\Models\Access;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DestyHelper
{
    public static function getToken()
    {
        try {
            $headers = [
                'Content-Type' => 'application/json',
                'Cookie' => 'acw_tc=0b3ab43217634384645796714e562272be5d9fa53edd1835bd663d1b2d5bf1'
            ];

            $body = [
                'applyId' => '4c710a33-9fcf-4786-990b-a78e0bf441c9',
                'username' => '+628112812779',
                'mobile' => '+628112812779'
            ];

            $response = Http::withHeaders($headers)
                ->timeout(30)
                ->post('https://api.desty.app/api/auth/token', $body);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['success'] && $data['code'] === "0") {
                    return self::storeToken($data['data']);
                }
            }

            Log::error('Desty Auth Failed', ['response' => $response->body()]);
            return null;

        } catch (\Exception $e) {
            Log::error('Desty Auth Exception: ' . $e->getMessage());
            return null;
        }
    }

    private static function storeToken($tokenData)
    {
        $expiredAt = now()->addDays(25);

        $access = Access::updateOrCreate(
            ['name' => 'desty'],
            [
                'token' => $tokenData['accessToken'],
                'expired_at' => $expiredAt
            ]
        );

        return $access;
    }

    public static function getValidToken()
    {
        return Access::where('name', 'desty')
            ->where('expired_at', '>', now())
            ->first();
    }

    public static function refreshTokenIfNeeded()
    {
        $token = self::getValidToken();
        
        if (!$token) {
            return self::getToken();
        }

        return $token;
    }

    public static function getAuthHeader()
    {
        $token = self::refreshTokenIfNeeded();
        
        if ($token) {
            return [
                'Authorization' => 'Bearer ' . $token->token,
                'Content-Type' => 'application/json'
            ];
        }

        return null;
    }
}
<?php

namespace App\Http\Controllers;

use App\Helpers\DestyHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DestyController extends Controller
{
    public function initialSync()
    {
        // Cek token valid
        $token = DestyHelper::getValidToken();
        
        if (!$token) {
            // Refresh token jika expired
            $token = DestyHelper::refreshTokenIfNeeded();
        }

        $yourApiAddress = env('APP_URL').'api/webhook/desty';

        // Gunakan token untuk API call
        $response = Http::withHeaders([
            'accessToken' => $token->token,
            'Content-Type' => 'application/json'
        ])->get('https://api.desty.app/'.$yourApiAddress);

        dd($response->json());

        return $response->json();
    }

    public function simpleWay()
    {
        // Cara paling simpel
        $response = Http::withHeaders(DestyHelper::getAuthHeader())
            ->get('https://api.desty.app/api/data');

        return $response->json();
    }
}

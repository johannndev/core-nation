<?php

namespace App\Http\Controllers;

use App\Helpers\DestyHelper;
use App\Models\DestyPayload;
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

        $yourApiAddress = env('APP_URL') . 'api/webhook/desty';

        // Gunakan token untuk API call
        $response = Http::withHeaders([
            'accessToken' => $token->token,
            'Content-Type' => 'application/json'
        ])->get('https://api.desty.app/' . $yourApiAddress);

        dd($response->json());

        return $response->json();
    }

    public function wh()
    {
        // Cek token valid
        $token = DestyHelper::getValidToken();

        if (!$token) {
            // Refresh token jika expired
            $token = DestyHelper::refreshTokenIfNeeded();
        }


        $response = Http::withHeaders([
            'Authorization'   => 'Bearer ' . $token->token,
            'Content-Type'  => 'application/json'
        ])->send('post', 'https://api.desty.app/api/warehouse/list', [
            'body' => json_encode([
                'pageNumber' => 0,
                'pageSize'   => 0
            ])
        ]);

        dd($response->json());

        return $response->json();
    }

    public function payload(Request $request)
    {
        $dataList = DestyPayload::orderBy('updated_at', 'desc');

        if ($request->invoice) {
            $dataList = $dataList->where('order_id', 'like', '%' . $request->invoice . '%');
        }

        // if($request->status == 'warning'){
        //     $dataList = $dataList->where('status',2)->where('error_type',2);
        // }elseif($request->status == 'success'){
        //     $dataList = $dataList->where('status',2)->where('error_type',10);
        // }elseif($request->status == 'error'){
        //     $dataList = $dataList->where('status',1)->where('error_type',1);
        // }else{
        //     if(!$request->invoice){
        //         $dataList = $dataList->where('status',0);
        //     }

        // }

        $dataList = $dataList->paginate(200)->withQueryString();

        // dd($allRolesInDatabase);

        return view('desty.payload', compact('dataList'));
    }

    public function detailPayload($id)
    {
        $data = DestyPayload::find($id);

        // path lengkap file JSON di public/
        $fullPath = public_path($data->json_path);

        // cek apakah file ada
        if (!file_exists($fullPath)) {
            abort(404, 'JSON file not found');
        }

        // baca file JSON
        $jsonContent = file_get_contents($fullPath);

        // decode JSON
        $jsonData = json_decode($jsonContent, true);


        return view('desty.detail_payload', compact('data', 'jsonData'));
    }

    public function simpleWay()
    {
        // Cara paling simpel
        $response = Http::withHeaders(DestyHelper::getAuthHeader())
            ->get('https://api.desty.app/api/data');

        return $response->json();
    }
}

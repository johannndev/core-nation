<?php

namespace App\Http\Controllers;

use App\Models\Logjubelio;
use Illuminate\Http\Request;

class ApiJubelioController extends Controller
{
    public function order(Request $request){
        $secret = 'corenation2025';
        $content = trim($request->getContent());

        $sign = hash_hmac('sha256',$content . $secret, $secret, false);

        // $data = new Logjubelio();

        // $data->log = $request->items;

        // $data->save();

        return response()->json([
            'status' => 'ok',
            'transaction_status' =>$request->status,
            'item' =>$request->items,
        ], 200);
    }

    public function retur(Request $request){
        $secret = 'corenation2025';
        $content = trim($request->getContent());

        $sign = hash_hmac('sha256',$content . $secret, $secret, false);

        $signature = $request->header('Sign');

        // $data = new Logjubelio();

        // $data->log = $request->items;

        // $data->save();

        $data = $request->all(); 

        return response()->json([
            'status' => 'ok',
            'signature' => $signature,
            'received_data' => $data
        ], 200);
    }
}

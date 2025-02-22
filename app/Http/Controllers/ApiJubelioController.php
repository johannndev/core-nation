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

        $data = new Logjubelio();

        $data->log = $sign;

        $data->save();

        return '200';
    }

    public function retur(Request $request){
        $secret = 'corenation2025';
        $content = trim($request->getContent());

        $sign = hash_hmac('sha256',$content . $secret, $secret, false);

        $data = new Logjubelio();

        $data->log = $sign;

        $data->save();

        return '200';
    }
}

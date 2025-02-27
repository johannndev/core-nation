<?php

namespace App\Http\Controllers;

use App\Models\Logjubelio;
use Illuminate\Http\Request;

class LogJubelioController extends Controller
{
    public function index(){
        $data = Logjubelio::paginate(100);

        dd($data->toArray());
    }

    public function detail($id){
        $data = Logjubelio::find($id);

        dd($data->toArray());
    }
    
}

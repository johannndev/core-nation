<?php

namespace App\View\Components\Customer;

use App\Helpers\JubelioHelper;
use App\Models\Customer;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\Component;

class Detail extends Component
{
    /**
     * Create a new component instance.
     */
    public $cid;
    public $nameType;
    public function __construct( $cid,$nameType)
    {
        $this->cid = $cid;
        $this->nameType =$nameType;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $data = Customer::withTrashed()->findOrFail($this->cid);

        $custLokal = $data->locations->pluck('name','id')->toArray();
        $userLokal = Auth::user()->location_id;

       
        if($userLokal > 0){
            if($data->locations){
                if(array_key_exists($userLokal,$custLokal)){
                    
                }else{
                    abort(404);
                }
            }
        }

        $hideProp = "show";

        if($data->type == Customer::TYPE_WAREHOUSE){
            $hideProp = "hidden";
        }

        if($data->type == Customer::TYPE_VWAREHOUSE){
            $hideProp = "hidden";
        }

        $jubelio = [];

        if($data->jubelio_location_id){
            $dataApi = JubelioHelper::checkOrUpdateData('jub', 'new_value');

            $response = Http::withHeaders([
                'Authorization' => $dataApi->sk
            ])->get('https://api2.jubelio.com/locations/'.$data->jubelio_location_id);
    
            $jubelio = $response->json();

            // dd($jubelio);
        }

        

   

       
        return view('components.customer.detail',compact('data','hideProp','jubelio'));
    }
}

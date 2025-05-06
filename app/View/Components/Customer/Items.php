<?php

namespace App\View\Components\Customer;

use App\Models\Customer;
use App\Models\WarehouseItem;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Items extends Component
{
    /**
     * Create a new component instance.
     */
    public $cid;
    public function __construct( $cid)
    {
        $this->cid = $cid;
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

        $query = WarehouseItem::with('item', 'item.group','item.sizeTag')->where('warehouse_id','=',$this->cid);

        if(Request('name')) {

            $name = Request('name');

			$query = $query->whereHas('item', function($query) use($name) {
				$query->where('code','LIKE', "%$name%");
			});
		}

        if(Request('show0')){
            $query = $query->where('quantity','>=',0);
        } else {
            $query = $query->where('quantity','>',0);
        }

        if (Request('sort') == 'qtyasc') {
            $query = $query->orderBy('quantity',  'asc');
        }elseif(Request('sort') == 'codedesc'){
            $query = $query->whereHas('item', function($query)  {
				$query->orderBy('code','desc');
			});
        }elseif(Request('sort') == 'codeasc'){
            $query = $query->whereHas('item', function($query)  {
				$query->orderBy('code','asc');
			});
        }elseif(Request('sort') == 'namedesc'){
            $query = $query->whereHas('item', function($query)  {
				$query->orderBy('name','desc');
			});
        }elseif(Request('sort') == 'nameasc'){
            $query = $query->whereHas('item', function($query)  {
				$query->orderBy('name','asc');
			});
        }elseif(Request('sort') == 'iddesc'){
            $query = $query->whereHas('item', function($query)  {
				$query->orderBy('id','desc');
			});
        }elseif(Request('sort') == 'idasc'){
            $query = $query->whereHas('item', function($query)  {
				$query->orderBy('id','asc');
			});
        } else {
            $query = $query->orderBy('quantity',  'desc');
        }
        

        $query =  $query->paginate(1000)->withQueryString();

        $dataList = $query;
        
        return view('components.customer.items',compact('dataList'));
    }
}

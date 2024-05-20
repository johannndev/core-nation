<?php

namespace App\View\Components\Customer;

use App\Models\WarehouseItem;
use Closure;
use Illuminate\Contracts\View\View;
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
        $query = WarehouseItem::with('item', 'item.group','item.sizeTag')->where('warehouse_id','=',$this->cid);

        if(Request('name')) {

            $name = Request('name');

			$query = $query->whereHas('item', function($query) use($name) {
				$query->where('code','LIKE', "%$name%");
			});
		}

        if(Request('show0')){
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
        } else {
            $query = $query->orderBy('quantity',  'desc');
        }
        

        $query =  $query->paginate(25)->withQueryString();

        $dataList = $query;
        
        return view('components.customer.items',compact('dataList'));
    }
}

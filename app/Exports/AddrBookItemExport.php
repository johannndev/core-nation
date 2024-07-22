<?php

namespace App\Exports;

use App\Models\Customer;
use App\Models\WarehouseItem;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AddrBookItemExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $sort,$cid,$name,$show0;

    public function __construct($cid,$name,$sort,$show0)
    {
        $this->sort = $sort;
        $this->cid = $cid;
        $this->name = $name;
        $this->show0 = $show0;
    }

    public function view(): View
    {
        $query = WarehouseItem::with('item', 'item.group','item.sizeTag')->where('warehouse_id','=',$this->cid);

        if($this->name) {

            $name = $this->name;

			$query = $query->whereHas('item', function($query) use($name) {
				$query->where('code','LIKE', "%$name%");
			});
		}

        if($this->show0){
            $query = $query->where('quantity','>',0);
        }

        if ($this->sort == 'qtyasc') {
            $query = $query->orderBy('quantity',  'asc');
        }elseif($this->sort == 'codedesc'){
            $query = $query->whereHas('item', function($query)  {
				$query->orderBy('code','desc');
			});
        }elseif($this->sort == 'codeasc'){
            $query = $query->whereHas('item', function($query)  {
				$query->orderBy('code','asc');
			});
        }elseif($this->sort == 'namedesc'){
            $query = $query->whereHas('item', function($query)  {
				$query->orderBy('name','desc');
			});
        }elseif($this->sort == 'nameasc'){
            $query = $query->whereHas('item', function($query)  {
				$query->orderBy('name','asc');
			});
        } else {
            $query = $query->orderBy('quantity',  'desc');
        }
        

        $query =  $query->paginate(25)->withQueryString();


        $dataList = $query;

        return view('export.ab_item', [
            'dataList' => $dataList
        ]);

    }

}

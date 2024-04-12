<?php

namespace App\View\Components\Transaction;

use App\Helpers\StockManagerHelpers;
use App\Models\WarehouseItem as ModelsWarehouseItem;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WarehouseItem extends Component
{
    /**
     * Create a new component instance.
     */

    public $idItem;
    public $arrayStokWh;
    public $arrayNameWh;



    public function __construct($idItem)
    {
        $this->idItem = $idItem;

        $whGet = StockManagerHelpers::$list;

        $nameWh = StockManagerHelpers::$names;

        $stok = ModelsWarehouseItem::with('warehouse')->whereIn('warehouse_id',$whGet)->where('item_id',$this->idItem)->pluck('quantity','warehouse_id')->toArray();

        $this->arrayStokWh = $stok;
        $this->arrayNameWh = $nameWh;

    
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.transaction.warehouse-item');
    }
}

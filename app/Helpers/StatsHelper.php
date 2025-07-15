<?php

namespace App\Helpers;

use App\Models\Transaction;

class StatsHelper
{
    function getSell($id, $type)
    {
        if($type == Transaction::TYPE_SELL){
            $addrIdCol = 'receiver_id';
            $addrTypeCol = 'receiver_type';
        }

        // Contoh implementasi: ambil data penjualan berdasarkan ID dan tipe
        return Transaction::where($addrIdCol,$id)->where( $addrTypeCol,$type);
    }
}





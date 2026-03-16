<?php

namespace App\Services\Transaction;

use Illuminate\Support\Facades\DB;

class StatsSalesService
{
    public function updateOrCreate(array $data): void
    {
        foreach ($data as $entry) {
            $existing = DB::table('stat_sells')
                ->where('group_id', $entry['group_id'])
                ->where('bulan', $entry['bulan'])
                ->where('tahun', $entry['tahun'])
                ->where('sender_id', $entry['sender_id'])
                ->lockForUpdate()
                ->first();

            if ($existing) {
                DB::table('stat_sells')
                    ->where('id', $existing->id)
                    ->incrementEach([
                        'sum_qty'   => $entry['sum_qty'],
                        'sum_total' => $entry['sum_total'],
                    ]);
            } else {
                DB::table('stat_sells')->insert([
                    'group_id'   => $entry['group_id'],
                    'bulan'      => $entry['bulan'],
                    'tahun'      => $entry['tahun'],
                    'sender_id'  => $entry['sender_id'],
                    'type'       => $entry['type'],
                    'sum_qty'    => $entry['sum_qty'],
                    'sum_total'  => $entry['sum_total'],
                    'created_at'=> now(),
                    'updated_at'=> now(),
                ]);
            }
        }
    }
}

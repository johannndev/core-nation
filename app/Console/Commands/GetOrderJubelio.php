<?php

namespace App\Console\Commands;

use App\Helpers\CronHelper;
use App\Models\Crongetorder;
use App\Models\Crongetorderdetail;
use App\Models\Cronrun;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetOrderJubelio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jubelio:get-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get order';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $data = Crongetorder::with('orderDetail')->withCount('orderDetail')->orderBy('created_at', 'desc')->first();

            if (!$data) {
                throw new \Exception('Data Crongetorder tidak ditemukan.');
            }

            if ($data->status == 1) {
                throw new \Exception('Data Crongetorder tidak aktif.');
            }

            $dateFrom = Carbon::parse($data->from, 'Asia/Jakarta');
            $isoUtcDateFrom = $dateFrom->setTimezone('UTC')->toIso8601String();

            $dateTo = Carbon::parse($data->to, 'Asia/Jakarta');
            $isoUtcDateTo = $dateTo->setTimezone('UTC')->toIso8601String();

            $token = Cache::get('jubelio_data')['token'] ?? null;

            if (!$token) {
                throw new \Exception('Token Jubelio tidak ditemukan di cache.');
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'authorization' => $token,
            ])->get('https://api2.jubelio.com/sales/orders/', [
                'page' => $data->count+1,
                'pageSize' => 200,
                'transactionDateFrom' => $isoUtcDateFrom,
                'transactionDateTo' => $isoUtcDateTo
            ]);

            if ($response->failed()) {
                Log::error('API Jubelio gagal merespon', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                throw new \Exception('Gagal mendapatkan data dari API Jubelio. Status: ' . $response->status());
            }

            $responData = $response->json(); // atau json_decode($response->body(), true);

           
            if($data->total < 1){

                $a = $responData['totalCount'];
                $b = 200;

                $hasil = (int)ceil($a / $b);

                $data->total = $hasil;

                $data->save();
          
            }

            $dataArray = []; 

            if(count($responData['data']) > 0){

                foreach ($responData['data'] as $row) {
                    $dataArray[] = [
                        'get_order_id' => $data->id,
                        'order_id' =>  $row['salesorder_id'],
                        'invoice' => $row['salesorder_no'],
                        'location_id' => $row['location_name'],
                        'store_id' => $row['store_name'],
                        'status' => $row['internal_status'],
                        'is_canceled' => $row['is_canceled'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                DB::table('crongetorderdetails')->insert($dataArray);

                $data->increment('count');

            }else{
                $data->status = 1;

                $data->save();

                $cronStatus = Cronrun::where('name', 'get_order')->first();

                $cronStatus->status = 0;

                $cronStatus->save();

                CronHelper::refreshCronCache();

                
                Crongetorderdetail::where('get_order_id', $data->id)
                    ->whereNotIn('status', ['SHIPPED', 'COMPLETED'])
                    ->where(function ($query) {
                        $query->whereHas('transaksi')
                            ->orWhereHas('logJubelio');
                    })
                    ->delete();
            }

          
            // Lanjutkan proses dengan $data...

        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat mengambil data dari Jubelio API', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Jika ingin kirim respon ke front-end
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }


    }
}

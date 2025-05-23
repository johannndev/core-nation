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

            $dateFrom = $data->from."T00:00:00Z";
            $dateTo = $data->to."T00:00:00Z";

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
                'transactionDateFrom' => $dateFrom,
                'transactionDateTo' => $dateTo
            ]);

            Log::info('infocron: ' .json_decode($response->body(), true));

            if ($response->failed()) {
                Log::error('API Jubelio gagal merespon', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                throw new \Exception('Gagal mendapatkan data dari API Jubelio. Status: ' . $response->status());
            }

            $responData = $response->json(); // atau json_decode($response->body(), true);

            Log::info('info: ' .$responData);

           
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

                    if($row['is_canceled'] == 1){
                        $cancel = 'Y';
                    }else{
                        $cancel = 'N';
                    }

                    $dataArray[] = [
                        'get_order_id' => $data->id,
                        'order_id' =>  $row['salesorder_id'],
                        'invoice' => $row['salesorder_no'],
                        'location_id' => $row['location_name'],
                        'store_id' => $row['store_name'],
                        'status' => $row['internal_status'],
                        'is_canceled' => $cancel,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                DB::table('crongetorderdetails')->insert($dataArray);

                $data->increment('count');

            }else{

                if( $data->status == 0){

                       if($data->step == 1){

                            

                            // Crongetorderdetail::where('get_order_id', $data->id)
                            // ->whereNotIn('status', ['SHIPPED', 'COMPLETED']) 
                            // ->delete();

                            // Crongetorderdetail::where('get_order_id', $data->id)
                            // ->where('is_canceled', 'Y')
                            // ->delete();

                            // dd($data->step);

                            $data->step = 2;
                            $data->save(); 

                        }else if($data->step == 2){

                            Crongetorderdetail::where(function ($query) {
                                $query->whereHas('transaksi')
                                    ->orWhereHas('logJubelio');
                            })
                            ->delete();

                            $data->step = 3;
                            $data->status = 1;

                            $data->save(); 

                            $cronStatus = Cronrun::where('name', 'get_order')->first();

                            $cronStatus->status = 0;

                            $cronStatus->save();

                            CronHelper::refreshCronCache();

                        }

                }

               
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

<?php

namespace App\Http\Controllers;

use App\Helpers\DestyHelper;
use App\Models\DestyPayload;
use App\Models\DestyWarehouse;
use App\Models\Item;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DestyController extends Controller
{
    public function initialSync()
    {
        // Cek token valid
        $token = DestyHelper::getValidToken();

        if (!$token) {
            // Refresh token jika expired
            $token = DestyHelper::refreshTokenIfNeeded();
        }

        $yourApiAddress = env('APP_URL') . 'api/webhook/desty';

        // Gunakan token untuk API call
        $response = Http::withHeaders([
            'accessToken' => $token->token,
            'Content-Type' => 'application/json'
        ])->get('https://api.desty.app/' . $yourApiAddress);

        dd($response->json());

        return $response->json();
    }

    public function wh()
    {
        // Cek token valid
        $token = DestyHelper::getValidToken();

        if (!$token) {
            // Refresh token jika expired
            $token = DestyHelper::refreshTokenIfNeeded();
        }


        $response = Http::withHeaders([
            'Authorization'   => 'Bearer ' . $token->token,
            'Content-Type'  => 'application/json'
        ])->send('post', 'https://api.desty.app/api/warehouse/list', [
            'body' => json_encode([
                'pageNumber' => 1,
                'pageSize'   => 20
            ])
        ]);

        dd($response->json());

        return $response->json();
    }

    public function payload(Request $request)
    {
        $dataList = DestyPayload::with('warehouse')->orderBy('updated_at', 'desc');

        if ($request->invoice) {
            $dataList = $dataList->where('order_id', 'like', '%' . $request->invoice . '%');
        }

        if($request->status == 'processed'){
            $dataList = $dataList->where('status','processed');
        }elseif($request->status == 'error'){
            $dataList = $dataList->where('status','error');
        }elseif($request->status == 'failed'){
            $dataList = $dataList->where('status','failed');
        }else{
            $dataList = $dataList->where('status','pending');

        }

        $dataList = $dataList->paginate(200)->withQueryString();

        // dd($allRolesInDatabase);

        return view('desty.payload', compact('dataList'));
    }

    public function detailPayload($id)
    {
        $data = DestyPayload::find($id);

        // dd($data);

        // path lengkap file JSON di public/
        $fullPath = public_path($data->json_path);

        // cek apakah file ada
        // if (!file_exists($fullPath)) {
        //     abort(404, 'JSON file not found');
        // }

        // baca file JSON
        $jsonContent = file_get_contents($fullPath);

        // decode JSON
        $jsonData = json_decode($jsonContent, true);


        return view('desty.detail_payload', compact('data', 'jsonData'));
    }

    public function simpleWay()
    {
        // Cara paling simpel
        $response = Http::withHeaders(DestyHelper::getAuthHeader())
            ->get('https://api.desty.app/api/data');

        return $response->json();
    }

    public function cek()
    {
        $desty = DestyPayload::where('order_status_list', 'Completed')->where('status', 'pending')->orderBy('date', 'asc')->first();
        // dd($desty);

        if ($desty) {

            $destyWh = DestyWarehouse::with('destySync')->where('platform_warehouse_id', $desty->platform_warehouse_id)
                ->where('store_id', $desty->store_id)
                ->first();

            if ($destyWh && $destyWh->destySync) {

                $itemCodes = collect($desty->item_list)->pluck('code')->unique();

                // Ambil hanya kolom yang diperlukan
                $existingProducts = Item::whereIn('code', $itemCodes)
                    ->get(['id', 'code', 'name'])
                    ->keyBy('code'); // Index berdasarkan 'code' agar pencarian lebih cepat

                // Proses matching dengan map agar lebih efisien
                $groupedData = collect($desty->item_list)->partition(fn($item) => isset($existingProducts[$item['code']]));

                // dd($groupedData);

                $matched = $groupedData[0]->map(fn($item) => [
                    'itemId'   => $existingProducts[$item['code']]->id,
                    'code'     => $existingProducts[$item['code']]->code,
                    'name'     => $existingProducts[$item['code']]->name,
                    'quantity' => $item['quantity'],
                    'price'    => $item['price'],
                    'discount' => 0,
                    'subtotal' => $item['quantity'] * $item['price'],
                ])->values(); // Reset indeks array

                $notMatched = $groupedData[1]->values(); // Reset indeks array

                $createData = [];


                if ($notMatched->count() > 0) {
                    // Ambil item_code dari notMatched
                    $item_codes = array_column($notMatched->toArray(), 'code');

                    // Ubah menjadi string dengan koma sebagai pemisah
                    $notMatchedString = implode(", ", $item_codes);
                    
                    DestyPayload::where('id', $desty->id)->update(['status' => 'failed', 'info' => 'Item tidak ditemukan: ' . $notMatchedString]);

                } else {

                    if ($matched->count() > 0) {

                        $cekTransaksi = Transaction::where('type', Transaction::TYPE_SELL)->where('invoice', $dataApi['salesorder_no'])->first();

                        if ($cekTransaksi) {

                            
                        } else {


                            $dataOrder = [
                                "date" => Carbon::now()->toDateString(),
                                "due" => null,
                                "warehouse" => $destyWh->destySync->warehouse_id,
                                "customer" => $destyWh->destySync->customer_id,
                                "invoice" => $desty->invoice,
                                "note" => "generated by desty cron order",
                                "account" => "7204",
                                "amount" => null,
                                "paid" => null,
                                "addMoreInputFields" => $matched,
                                "disc" => "0",
                                "adjustment" =>  $desty->adjustment,
                                "ongkir" => "0"
                            ];

                            $dataCollect =  (object) $dataOrder;

                            $createData =  $this->createTransaction(Transaction::TYPE_SELL, $dataCollect);


                            if ($createData['status'] == "200") {

                                DestyPayload::where('id', $desty->id)->update(['status' => 'processed', 'info' => 'Order berhasil dibuat di sistem.']);
                            } else {

                               DestyPayload::where('id', $desty->id)->update(['status' => 'error', 'info' => 'Gagal membuat order: ' . $createData['message']]);
                            }
                        }
                    }
                }
            } else {
                DestyPayload::where('id', $desty->id)->update(['status' => 'failed', 'info' => 'Desty Warehouse tidak ditemukan atau belum disync']);
            }
        } else {
            dd("Tidak ada data untuk diproses");
        }
    }
}

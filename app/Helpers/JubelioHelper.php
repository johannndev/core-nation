<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class JubelioHelper
{
     /**
     * Cek atau update data berdasarkan slug
     *
     * @param string $slug
     * @param mixed|null $newKeyValue
     * @return object
     */

    public static function jubelioAuth(){

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://api2.jubelio.com/login', [
            'email' => 'johanwebdev@gmail.com',
            'password' => 'aY6J9gXZVQ!'
        ]);
        
        if ($response->successful()) {
            return $response->json();
        }

        return null;

    }

    public static function checkOrUpdateData(string $slug, $newKeyValue = null)
    {
        // Cek apakah data dengan slug tersebut ada
        $data = DB::table('ams')->where('name', $slug)->first();

        if (!$data) {

            $jubelioApi = self::jubelioAuth();
            // Jika data tidak ditemukan, buat baru
            $newData = [
                'name' => $slug,
                'sk' => $jubelioApi['token'] ?? 'default_value',
                'expDate' => Carbon::now()->addHours(10), // Expired dalam 10 jam
                'created_at' => now(),
                'updated_at' => now(),
            ];
            DB::table('ams')->insert($newData);
            return (object) $newData;
        }

        // Jika data ditemukan, cek tanggal expired
        if (Carbon::parse($data->expDate)->isPast()) {
            // Update jika sudah expired
            $jubelioApi = self::jubelioAuth();

            DB::table('ams')
                ->where('name', $slug)
                ->update([
                    'key' => $jubelioApi['token'] ?? $data->key,
                    'expDate' => Carbon::now()->addHours(10),
                    'updated_at' => now(),
                ]);

            $data->key = $jubelioApi['token'] ?? $data->key;
            $data->expDate = Carbon::now()->addHours(10)->toDateTimeString();
        }

        return $data;
    }

}





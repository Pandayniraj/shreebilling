<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class NepalIRDSync extends Model
{
    public function postbill($data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
              CURLOPT_URL => 'http://103.1.92.174:9050/api/bill',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>$data,
              CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
              ],
            ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'cURL Error #:'.$err;
        } else {
            return $response;
        }
    }

    public function returnbill($data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
              CURLOPT_URL => 'http://43.245.85.152:9050/api/bill',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>$data,
              CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
              ],
            ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 'cURL Error #:'.$err;
        } else {
            return $response;
        }
    }
}

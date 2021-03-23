<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

class GuzzleController extends Controller
{
    public function guzzleGet()
    {
        //使用guzzle
        $client = new Client();
        $request = $client->get("http://10.249.33.229/~po-hsiang/EasyRegisterLogin/public/api/products");
        $response = $request->getBody();

        //使用curl
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        // CURLOPT_URL => 'http://10.249.33.229/~po-hsiang/EasyRegisterLogin/public/api/products',
        // CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => '',
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 5,
        // CURLOPT_SSL_VERIFYHOST =>0,
        // CURLOPT_SSL_VERIFYPEER => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => 'GET',
        // ));

        // $response = curl_exec($curl);

        // curl_close($curl);
        // if ($response == null) {
        //     echo "伺服器沒有回應";
        // }

        return $response;
    }

    public function guzzlePost(Request $request)
    {
        $name = $request->name;
        $detail = $request->detail;
        
        //使用guzzle
        $client = new Client();
        $response = $client->post('http://10.249.33.229/~po-hsiang/EasyRegisterLogin/public/api/products', [
            'json' => [
                'name' => $name,
                'detail' => $detail,
            ],
        ])->getBody()->getContents();

        //使用curl
        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        // CURLOPT_URL => 'http://10.249.33.229/~po-hsiang/EasyRegisterLogin/public/api/products',
        // CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => '',
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_SSL_VERIFYHOST =>0,
        // CURLOPT_SSL_VERIFYPEER => 0,
        // CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => 'POST',
        // CURLOPT_POSTFIELDS => "name=$name&detail=$detail",
        // CURLOPT_HTTPHEADER => array(
        //     'Content-Type: application/x-www-form-urlencoded'
        // ),
        // ));
        // $response = curl_exec($curl);
        // curl_close($curl);

        return $response;
    }

    public function guzzleShow($id)
    {
        //使用 guzzle
        $client = new Client();
        $request = $client->get("http://10.249.33.229/~po-hsiang/EasyRegisterLogin/public/api/products/$id");
        $response = $request->getBody();

        //使用 curl
        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        // CURLOPT_URL => "http://10.249.33.229/~po-hsiang/EasyRegisterLogin/public/api/products/$id",
        // CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => '',
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => 'GET',
        // ));
        // $response = curl_exec($curl);
        // curl_close($curl);

        return $response;
    }

    public function guzzlePatch($id, Request $request)
    {
        $name = $request->name;
        $detail = $request->detail;
        
        //使用 guzzle
        $client = new Client();
        $response = $client->patch("http://10.249.33.229/~po-hsiang/EasyRegisterLogin/public/api/products/$id", [
            'form_params' => [
                'name' => $name,
                'detail' => $detail,
            ],
        ])->getBody();

       
        //使用 curl
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        // CURLOPT_URL => "http://10.249.33.229/~po-hsiang/EasyRegisterLogin/public/api/products/$id",
        // CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => '',
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => 'PATCH',
        // CURLOPT_POSTFIELDS =>"name=$name&detail=$detail",
        // CURLOPT_HTTPHEADER => array(
        //     'Content-Type: application/x-www-form-urlencoded'
        // ),
        // ));

        // $response = curl_exec($curl);

        // curl_close($curl);

        return $response;
    }

    public function guzzleDestroy($id)
    {
        //使用guzzle
        $client = new Client();
        $request = $client->delete("http://10.249.33.229/~po-hsiang/EasyRegisterLogin/public/api/products/$id");
        $response = $request->getBody();


        //使用 curl
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        // CURLOPT_URL => "http://10.249.33.229/~po-hsiang/EasyRegisterLogin/public/api/products/$id",
        // CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_ENCODING => '',
        // CURLOPT_MAXREDIRS => 10,
        // CURLOPT_TIMEOUT => 0,
        // CURLOPT_FOLLOWLOCATION => true,
        // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        // CURLOPT_CUSTOMREQUEST => 'DELETE',
        // ));
        // $response = curl_exec($curl);
        // curl_close($curl);


        return $response;
    }
    public function guzzleSearch(Request $request)
    {
        $keywords = $request->keywords;
        //使用guzzle
        $client = new Client();
        $response = $client->post('http://10.249.33.229/~po-hsiang/EasyRegisterLogin/public/api/search', [
            'json' => [
                'keywords' => $keywords,
            ],
        ])->getBody()->getContents();


        // //使用 curl
        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //   CURLOPT_URL => 'http://10.249.33.229/~po-hsiang/EasyRegisterLogin/public/api/search',
        //   CURLOPT_RETURNTRANSFER => true,
        //   CURLOPT_ENCODING => '',
        //   CURLOPT_MAXREDIRS => 10,
        //   CURLOPT_TIMEOUT => 0,
        //   CURLOPT_FOLLOWLOCATION => true,
        //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //   CURLOPT_CUSTOMREQUEST => 'POST',
        //   CURLOPT_POSTFIELDS => "keywords=$keywords",
        //   CURLOPT_HTTPHEADER => array(
        //     'Content-Type: application/x-www-form-urlencoded'
        //   ),
        // ));
        
        // $response = curl_exec($curl);
        
        // curl_close($curl);

        return $response;
    }
}
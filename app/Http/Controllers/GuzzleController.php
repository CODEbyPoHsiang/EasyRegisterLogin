<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

class GuzzleController extends Controller
{
    public function guzzleGet()
    {
        $client = new \GuzzleHttp\Client();
        $request = $client->get('http://10.249.33.229/~po-hsiang/EasyRegisterLogin/public/api/products');
        $response = $request->getBody();
   
        return ($response);
    }

    public function guzzlePost(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function update($id, Request $request)
    {
    }

    public function destroy($id)
    {
    }
}

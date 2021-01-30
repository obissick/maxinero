<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Setting;
use Auth;
use GuzzleHttp\Promise;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

class GuzzleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function get_request($location){

        $setting = $this->get_api_info();
        if(isset($setting)){
            $client = new Client();
            $res = $client->request('GET', $setting->api_url.$location, [
                'auth' => [$setting->username, Crypt::decrypt($setting->password)], 
                'verify' => false,
                'timeout' => 5.00
            ]);
            return $res->getBody()->getContents();
        }
        else{
            throw new \Exception("No Maxscale server found.");
        }
    }

    function post_request($data, $location){
        $setting = $this->get_api_info();
        $client = new Client();
        $res = $client->request('POST', $setting->api_url.$location, [
            'headers'  => ['content-type' => 'application/json', 'Accept' => 'application/json'],
            'auth' => [$setting->username, Crypt::decrypt($setting->password)], 
            'verify' => false,
            'body' => json_encode($data)
        ]);
        return $res;
    }

    function delete_request($location){
        $setting = $this->get_api_info();
        $client = new Client();
        $res = $client->delete($setting->api_url.$location, [
            'auth' => [$setting->username, Crypt::decrypt($setting->password)], 
            'verify' => false
        ]);
        return $res;
    }

    function put_request($location){
        $setting = $this->get_api_info();
        $client = new Client();
        $res = $client->put($setting->api_url.$location, [
            'auth' => [$setting->username, Crypt::decrypt($setting->password)], 
            'verify' => false
        ]);
        return $res;
    }

    function put_data($data, $location){
        $setting = $this->get_api_info();
        $client = new Client();
        $res = $client->patch($setting->api_url.$location, [
            'headers'  => ['content-type' => 'application/json', 'Accept' => 'application/json'],
            'auth' => [$setting->username, Crypt::decrypt($setting->password)], 
            'verify' => false,
            'body' => json_encode($data)
        ]);
        return $res;
    }

    function get_api_info(){
        return DB::table('settings')
            ->select(DB::raw('id, api_url, username, password'))
            ->where([['user_id', '=', Auth::user()->id],['selected', '=', true]])->first();
    }
}

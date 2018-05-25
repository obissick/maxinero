<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Setting;
use Auth;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function get_request($location){
        $setting = $this->get_api_info();
        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', $setting->api_url.$location, [
            'auth' => [$setting->username, Crypt::decrypt($setting->password)], 
            'verify' => false
        ]);
        return $res->getBody()->getContents();
    }

    function post_request($data, $location){
        $setting = $this->get_api_info();
        $client = new GuzzleHttp\Client();
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
        $client = new GuzzleHttp\Client();
        $res = $client->delete($setting->api_url.$location, [
            'auth' => [$setting->username, Crypt::decrypt($setting->password)], 
            'verify' => false
        ]);
    }

    function get_api_info(){
        return DB::table('settings')
            ->select(DB::raw('id, api_url, username, password'))
            ->where('user_id', Auth::user()->id)->first();
    }
}

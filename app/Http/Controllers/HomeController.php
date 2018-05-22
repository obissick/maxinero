<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Setting;
use Auth;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $sessions = $this->max_request('GET', 'sessions');
        $count = count($sessions['data']);

        return view('dash.view', compact('count', 'sessions'));
    }

    function max_request($type, $location){
        $setting = DB::table('settings')
            ->select(DB::raw('id, api_url, username, password'))
            ->where('user_id', Auth::user()->id)->first();

        $client = new GuzzleHttp\Client();
        $res = $client->request($type, $setting->api_url.$location, [
            'auth' => [$setting->username, Crypt::decrypt($setting->password)], 'verify' => false
        ]);
        return json_decode($res->getBody()->getContents(), true);
    }
}

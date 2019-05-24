<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Setting;
use Auth;

class HomeController extends Controller
{
    public $guzzle;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->guzzle = \App::make('App\Http\Controllers\GuzzleController');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {  
        try{
            $threads = json_decode($this->guzzle->get_request('maxscale/threads'), true);
            $sessions = json_decode($this->guzzle->get_request('sessions'), true);
            $count = count($sessions['data']);
            $threads_count = count($threads['data']);
            return view('dash.view', compact('count', 'sessions', 'threads_count', 'threads'));

        } catch(\GuzzleHttp\Exception\ConnectException $exception){
            return redirect('settings')->with('error', 'Issue connecting to MaxScale backend.');
        } catch(\GuzzleHttp\Exception\RequestException $exception){
            return redirect('settings')->with('error', 'Issue connecting to MaxScale backend.');
        }
    }
}

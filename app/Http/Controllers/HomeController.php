<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Setting;
use Auth;

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
        $threads = json_decode($this->get_request('maxscale/threads'), true);
        $sessions = json_decode($this->get_request('sessions'), true);
        $count = count($sessions['data']);
        $threads_count = count($threads['data']);

        return view('dash.view', compact('count', 'sessions', 'threads_count', 'threads'));
    }
}

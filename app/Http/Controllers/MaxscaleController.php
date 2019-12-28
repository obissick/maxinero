<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use View;
use Redirect;

class MaxscaleController extends Controller
{
    public $guzzle;

    public function __construct()
    {
        $this->middleware('auth');
        $this->guzzle = \App::make('App\Http\Controllers\GuzzleController');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $maxscale = json_decode($this->guzzle->get_request('maxscale'), true);
            $log = $this->guzzle->get_request('maxscale/logs');
            return view('maxinfo', compact('maxscale','log'));
            
        } catch(\GuzzleHttp\Exception\ConnectException $exception){
            return redirect('settings')->with('error', 'Issue connecting to MaxScale backend.');
        }
        catch(\Exception $exception){
            return redirect('settings')->with('error', $exception->getMessage());
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function flush_log(Request $request)
    {
        try{
            $this->guzzle->post_request([],'maxscale/logs/flush');
            Session::flash('success', 'Log flushed!');
            return View::make('flash-message');
        } catch(\GuzzleHttp\Exception\ConnectException $exception){
            return redirect('settings')->with('error', 'Issue connecting to MaxScale backend.');
        } catch(\GuzzleHttp\Exception\RequestException $exception){
            return redirect('maxscale')->with('error', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonitorController extends Controller
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
        //
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
        $this->validate_monitor($request);
        $data = $this->prep_data($request);
        $res = $this->guzzle->post_request($data, 'monitors');
        return $this->guzzle->get_request('monitors/'.$request->input('monitor_id'));
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
        $monitor = $this->guzzle->get_request('monitors/'.$id);
        return $monitor;
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
        $this->validate_monitor($request);
        $data = $this->prep_data($request);
        $res = $this->guzzle->put_data($data, 'monitors/'.$id);
        return $this->guzzle->get_request('monitors/'.$request->input('monitor_id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = preg_replace('#[ -]+#', '-', $id);
        $this->guzzle->delete_request('monitors/'.$id);
    }

    public function validate_monitor(Request $request){
        return $this->validate($request,[
            'monitor_id' => 'required',
            'monitor_type' => 'required',
            'module' => 'required',
            'monitor_interval' => 'required'
        ]);
    }

    public function change_state(Request $request, $id){
        $type = $request->input('type');
        $this->guzzle->put_request('monitors/'.$id.'/'.$type);
        return $this->guzzle->get_request('monitors/'.$id);
    }

    public function prep_data(Request $request){
        $servers = explode(',', trim($request->input('servers')));
        
        $relation_data = array();
        for ($i = 0; $i < count($servers); $i++){
            $relation_data[$i]['id'] = $servers[$i];
            $relation_data[$i]['type'] = 'servers';
        }

        if($servers[0]==""){
            $data = array(
                'data' => [
                'id' => $request->input('monitor_id'),
                'type' => $request->input('monitor_type'),
                'attributes' => [
                    'module' => $request->input('module'),
                    'parameters' => [
                        'monitor_interval' => (int) $request->input('monitor_interval'),
                        'user' => $request->input('user'),
                        'password' => $request->input('password')
                    ]
                ]
                ]);
        }else{
            $data = array(
                'data' => [
                    'id' => $request->input('monitor_id'),
                    'type' => $request->input('monitor_type'),
                    'attributes' => [
                        'module' => $request->input('module'),
                        'parameters' => [
                            'monitor_interval' => (int) $request->input('monitor_interval'),
                            'user' => $request->input('monuser'),
                            'password' => $request->input('monpass')
                        ]
                    ],
                'relationships' => [
                    'servers' => [
                        'data' => $relation_data
                    ]
                ]
            ]);
        }
        return $data;
    }
}

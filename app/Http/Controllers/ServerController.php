<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Setting;
use Auth;

class ServerController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $servers = json_decode($this->get_request('servers'), true);
        return view('servers.servers', compact('servers'));
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
        $services = explode(',', trim($request->input('services')));
        
        $relation_data = array();
        for ($i = 0; $i < count($services); $i++){
            $relation_data[$i]['id'] = $services[$i];
            $relation_data[$i]['type'] = 'services';
        }
        #dd($relation_data);

        if($services[0]==""){
            $data = array(
                'data' => [
                'id' => $request->input('server_id'),
                'type' => 'servers',
                'attributes' => [
                    'parameters' => [
                        'address' => $request->input('address'),
                        'port' => (int) $request->input('port'),
                        'protocol' => $request->input('protocol')
                    ]
                ]
                ]);
        }else{
            $data = array(
                'data' => [
                'id' => $request->input('server_id'),
                'type' => 'servers',
                'attributes' => [
                    'parameters' => [
                        'address' => $request->input('address'),
                        'port' => (int) $request->input('port'),
                        'protocol' => $request->input('protocol')
                    ]
                ],
                'relationships' => [
                    'services' => [
                        'data' => $relation_data
                    ]
                ]
            ]);
        }
        $res = $this->post_request($data, 'servers');
        return $this->get_request('servers/'.$request->input('server_id'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $server = $this->get_request('servers/'.$id);
        #dd(json_decode($server));
        return $server;
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = preg_replace('#[ -]+#', '-', $id);
        $this->delete_request('servers/'.$id);
    }
    
}

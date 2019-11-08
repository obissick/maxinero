<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Setting;
use Auth;
use Session;
use View;
use App\ServiceStats;

class ServerController extends Controller
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
            $maxserver = $this->guzzle->get_api_info();
            $server_stat = DB::table('server_stats')
                ->select(DB::raw('created_at, sum(connections) AS sum, sum(active_operations) AS sum_ops'))
                ->where('setting_id', $maxserver->id)
                ->whereRaw('created_at > (CONVERT_TZ( NOW(), @@session.time_zone, \'+00:00\') - INTERVAL 1 HOUR)')
                ->groupBy('created_at')
                ->orderBy('created_at')
                ->get()->toArray();
        
            $times = array_column($server_stat, 'created_at');
            $sum_conn = array_column($server_stat, 'sum');
            $sum_ops = array_column($server_stat, 'sum_ops');
            
            $servers = json_decode($this->guzzle->get_request('servers'), true);
            return view('servers.servers', compact('servers'))
                ->with('times',json_encode($times,JSON_NUMERIC_CHECK))
                ->with('sum_conn',json_encode($sum_conn,JSON_NUMERIC_CHECK))
                ->with('sum_ops',json_encode($sum_ops,JSON_NUMERIC_CHECK));
            
        } catch(\GuzzleHttp\Exception\ConnectException $exception){
            return redirect('settings')->with('error', 'Issue connecting to MaxScale backend.');
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
        $this->validate($request,[
            'server_id' => 'required',
            'address' => 'required',
            'port' => 'required',
            'protocol' => 'required',
            'address' => 'required'
            ]);

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
        $res = $this->guzzle->post_request($data, 'servers');
        return $this->guzzle->get_request('servers/'.$request->input('server_id'));
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $server = json_decode($this->guzzle->get_request('servers/'.$id), true);
        //dd(json_decode($server));
        return view('servers.serverdetail', compact('server'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $server = $this->guzzle->get_request('servers/'.$id);
        return $server;
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
        $this->validate($request,[
            'server_id' => 'required',
            'address' => 'required',
            'port' => 'required',
            'protocol' => 'required',
            'address' => 'required'
            ]);

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
        $res = $this->guzzle->put_data($data, 'servers/'.$id);
        return $this->guzzle->get_request('servers/'.$request->input('server_id'));
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
        $this->guzzle->delete_request('servers/'.$id);
        Session::flash('success', 'Server deleted.');
        return View::make('flash-message');
    }

    public function change_state(Request $request, $id)
    {
        $server = $this->guzzle->get_request('servers/'.$id);
        $server = json_decode($server, true);
        $state = $request->input('state');
        $states = explode(',', trim($server['data']['attributes']['state']));
        if(in_array($state, $states)){
            $res = $this->guzzle->put_request('servers/'.$id.'/clear?state='.$state);
        }else{
            $res = $this->guzzle->put_request('servers/'.$id.'/set?state='.$state.'&force=yes');
        }  
        sleep(1);
        return $this->guzzle->get_request('servers/'.$id);
    }
    
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceStats;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
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
            
            $services = json_decode($this->guzzle->get_request('services'), true);
            $monitors = json_decode($this->guzzle->get_request('monitors'), true);
            return view('services.services', compact('services', 'monitors'));
            
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
        $this->validate($request,[
            'service_id' => 'required',
            'service_type' => 'required',
            'module' => 'required',
            'user' => 'required',
            'password' => 'required'
        ]);

        $data = array(
            'data' => [
            'id' => $request->input('service_id'),
            'type' => $request->input('service_type'),
            'attributes' => [
                'router' => $request->input('module'),
                'parameters' => [
                    'user' => $request->input('user'),
                    'password' => $request->input('password')
                ]
            ]
        ]);

        try{
            $res = $this->guzzle->post_request($data, 'services/');
            return $this->guzzle->get_request('services/'.$request->input('service_id'));

        }catch(\GuzzleHttp\Exception\ClientException $exception){
            return redirect('services')->with('error', $exception->getResponse()->getBody(true));
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $maxserver = $this->guzzle->get_api_info();
        $service_stat = DB::table('service_stats')
            ->select(DB::raw('created_at, avg(connections) AS avg'))
            ->where('setting_id', $maxserver->id)
            ->where('service_id', $id)
            ->whereRaw('created_at > (CONVERT_TZ( NOW(), @@session.time_zone, \'+00:00\') - INTERVAL 1 HOUR)')
            ->groupBy('created_at')
            ->groupBy('service_id')
            ->orderBy('created_at')
            ->orderBy('service_id')
            ->get()->toArray();
        $times = array_column($service_stat, 'created_at');
        $avg_ctime = array_column($service_stat, 'avg');

        if($this->guzzle->get_api_info()){
            $service = json_decode($this->guzzle->get_request('services/'.$id), true);
            $listeners = json_decode($this->guzzle->get_request('services/'.$id.'/listeners'), true);
            return view('services.servicedetail', compact('service', 'listeners'))
                ->with('times',json_encode($times,JSON_NUMERIC_CHECK))
                ->with('avg_ctime',json_encode($avg_ctime,JSON_NUMERIC_CHECK));
        }else{
            return view('setting.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service = $this->guzzle->get_request('services/'.$id);
        return $service;
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
            'service_id' => 'required',
            'service_type' => 'required',
            'module' => 'required',
            'user' => 'required',
            'password' => 'required'
        ]);

        $data = array(
            'data' => [
            'id' => $request->input('service_id'),
            'type' => $request->input('service_type'),
            'attributes' => [
                'router' => $request->input('module'),
                'parameters' => [
                    'user' => $request->input('user'),
                    'password' => $request->input('password')
                ]
            ]
        ]);

        try{
            $res = $this->guzzle->put_data($data, 'services/'.$id);
            return $this->guzzle->get_request('services/'.$request->input('service_id'));

        }catch(\GuzzleHttp\Exception\ClientException $exception){
            #return redirect('services')->with('error', $exception->getResponse()->getBody(true));
            #return view('services.services')->with('error', $exception->getResponse()->getBody(true));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //$service = $request->input('service');
        $id = preg_replace('#[ -]+#', '-', $id);
        $this->guzzle->delete_request('services/'.$id);
    }
    public function create_listener(Request $request, $id)
    {
        
        $listener = $request->input('listener_id');
        $id = preg_replace('#[ -]+#', '-', $id);
        $data = array(
            'data' => [
            'id' => $request->input('listener_id'),
            'type' => $request->input('listener_type') ?: "listeners",
            'attributes' => [
                'parameters' => [
                    'port' => (int) $request->input('port')
                ]
            ]
        ]);
        if(!empty($request->input('address'))) $data['data']['attributes']['parameters']['address'] = $request->input('address'); 
        if(!empty($request->input('protocol'))) $data['data']['attributes']['parameters']['protocol'] = $request->input('protocol');
        if(!empty($request->input('auth'))) $data['data']['attributes']['parameters']['authenticator'] = $request->input('auth');
        if(!empty($request->input('auth_options'))) $data['data']['attributes']['parameters']['authenticator_options'] = $request->input('auth_options');
        if(!empty($request->input('ssl_key'))) $data['data']['attributes']['parameters']['ssl_key'] = $request->input('ssl_key');
        if(!empty($request->input('ssl_cert'))) $data['data']['attributes']['parameters']['ssl_cert'] = $request->input('ssl_cert');
        if(!empty($request->input('ssl_ca_cert'))) $data['data']['attributes']['parameters']['ssl_ca_cert'] = $request->input('ssl_ca_cert');
        if(!empty($request->input('ssl_version'))) $data['data']['attributes']['parameters']['ssl_version'] = $request->input('ssl_version');
        if(!empty($request->input('ssl_depth'))) $data['data']['attributes']['parameters']['ssl_cert_verify_depth'] = $request->input('ssl_depth');

        $res = $this->guzzle->post_request($data, 'services/'.$id.'/listeners');
        return $this->guzzle->get_request('services/'.$id.'/listeners'.'/'.$listener);
        
    }
    public function destroy_listener(Request $request, $id)
    {
        try{
            $listener = $request->input('listener');
            $id = preg_replace('#[ -]+#', '-', $id);
            $this->guzzle->delete_request('services/'.$id.'/listeners'.'/'.$listener);
        } catch(\GuzzleHttp\Exception\ClientException $exception){
            $pos = strpos($exception->getMessage(),"was not created at runtime");
            if($pos === false) {
                
            }
            else {
                $type = 'error';
                $errmessage = "Listener was not created at runtime. Remove listener manually.";
            }
            return response()->json([$type, $errmessage]);
        }
        
    }

    public function change_state(Request $request, $id){
        $type = $request->input('type');
        $this->guzzle->put_request('services/'.$id.'/'.$type);
        return $this->guzzle->get_request('services/'.$id);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceController extends Controller
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
        if($this->get_api_info()){
            $services = json_decode($this->get_request('services'), true);
            $monitors = json_decode($this->get_request('monitors'), true);
            return view('services.services', compact('services', 'monitors'));
        }else{
            return view('setting.index');
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
        if($this->get_api_info()){
            $service = json_decode($this->get_request('services/'.$id), true);
            $listeners = json_decode($this->get_request('services/'.$id.'/listeners'), true);
            return view('services.servicedetail', compact('service', 'listeners'));
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
        $type = $request->input('type');
        $this->put_request('services/'.$id.'/'.$type);
        return $this->get_request('services/'.$id);
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
        $this->delete_request('services/'.$id);
    }
    public function destroy_listener(Request $request, $id)
    {
        $listener = $request->input('listener');
        $id = preg_replace('#[ -]+#', '-', $id);
        $this->delete_request('services/'.$id.'/listeners'.'/'.$listener);
    }
}

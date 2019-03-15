<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use View;

class UserController extends Controller
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
        try{
            
            $users = json_decode($this->get_request('users'), true);
            return view('users.users', compact('users'));
            
        } catch(\GuzzleHttp\Exception\ConnectException $exception){
            return redirect('settings')->with('error', 'Issue connecting to MaxScale backend.');
        }
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
            'user_id' => 'required',
            'password' => 'required',
            'account' => 'required'
        ]);

        $data = array(
            'data' => [
            'id' => $request->input('user_id'),
            'type' => 'inet',
            'attributes' => [
                'password' => $request->input('password'),
                'account' => $request->input('account')
            ]

        ]);

        $res = $this->post_request($data, 'users/inet');
        return $this->get_request('users/inet/'.$request->input('user_id'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->input('type')== 'delete-user'){
            $this->delete_request('users/inet/'.$id);
            Session::flash('success', 'User deleted.');
            return View::make('flash-message');
        }elseif($request->input('type')== 'disable-user'){
            $this->delete_request('users/unix/'.$id);
            Session::flash('success', 'User disabled.');
            return View::make('flash-message');
        }
    }
}

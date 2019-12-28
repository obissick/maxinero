<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Hash;
use Auth;
use Session;
use View;

class ProfileController extends Controller
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
        $user = DB::table('users')
            ->select(DB::raw('id, name, email,  created_at, updated_at'))
            ->where('id', Auth::user()->id)->first();
        $apis = DB::table('settings')
            ->select(DB::raw('count(id) as count'))
            ->where('user_id', Auth::user()->id)->first();
        return view('profile.index', compact('user','apis'));
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
        try{
            $timestamp = date('Y-m-d H:i:s');
            if(empty($request->get("password"))){
                $password = DB::table('users')
                    ->select(DB::raw('password'))
                    ->where('id', Auth::user()->id)->first();
                $password = $password->password;
            }else{
                $password = Hash::make($request->get('password'));
            }
            DB::table('users')->where('id', $id)->update([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => $password, 
                'updated_at' => $timestamp,
            ]);
            return back()->with('success','Profile updated successfully!');
        }catch(Exception $ex){
            return back()->with('error',$ex->getMessage());
        }
        
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect('home');
    }
}

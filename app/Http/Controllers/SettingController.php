<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Setting;
use Auth;
use Session;
use View;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $settings = DB::table('settings')
            ->select(DB::raw('id, name, api_url, username, password, selected'))
            ->where('user_id', Auth::user()->id)->get();
        return view('setting.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $this->validate_settings($request);
        $settings = new Setting([
            'name' => $request->get('api_name'),
            'api_url' => $request->get('api_url'),
            'username' => $request->get('api_username'),
            'password' => Crypt::encrypt($request->get('api_password')),
            'user_id' => Auth::user()->id,
            'selected' => 1,
        ]);

        $settings->save();
        return $settings->toJson();
    }

    public function edit($id)
    {
        $settings = Setting::find($id);

        return $settings->toJson();
    }

    public function update(Request $request, $id)
    {
        $this->validate_settings($request);
        DB::table('settings')->where('id', $id)->update([
            'name' => $request->get('api_name'),
            'api_url' => $request->get('api_url'),
            'username' => $request->get('api_username'),
            'password' => Crypt::encrypt($request->get('api_password')), 
        ]);
        return $this->edit($id);
    }

    public function select(Request $request, $id)
    {
        try{
            Setting::where('selected', 1)
            ->where('user_id', Auth::user()->id)
            ->update(['selected' => false]);

            Setting::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->update(['selected' => true]);
            Session::flash('success', 'Server selected.');
        } catch(Exception $exception){
            Session::flash('error', 'Error selecting server.');
        }
        return View::make('flash-message');
    }

    public function destroy($id)
    {
        Setting::find($id)->delete();
        Session::flash('success', 'MaxScale server deleted.');
        return View::make('flash-message');
    }
    public function validate_settings(Request $request){
        return $this->validate($request,[
            'api_name' => 'required',
            'api_url' => 'required',
            'api_username' => 'required',
            'api_password' => 'required'
        ]);
    }
}

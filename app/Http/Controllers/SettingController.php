<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Setting;
use Auth;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $settings = DB::table('settings')
            ->select(DB::raw('id, api_url, username, password, selected'))
            ->where('user_id', Auth::user()->id)->paginate(25);
        return view('setting.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $settings = new Setting([
            'api_url' => $request->get('api_url'),
            'username' => $request->get('api_username'),
            'password' => Crypt::encrypt($request->get('api_password')),
            'user_id' => Auth::user()->id,
            'selected' => false,
        ]);

        $settings->save();
        return redirect()->back()->with('status', 'MaxScale server added.');
    }

    public function edit($id)
    {
        $setting = Setting::find($id);
        return view('setting.index', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        Setting::find($id)->update([
            'api_url' => $request->get('api_url'),
            'username' => $request->get('api_username'),
            'password' => Crypt::encrypt($request->get('api_password')), 
            'selected' => false,
        ]);
        return redirect()->route('settings.index');
    }

    public function select(Request $request, $id)
    {
        Setting::where('selected', 1)
            ->where('user_id', Auth::user()->id)
            ->update(['selected' => false]);

        Setting::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->update(['selected' => true]);

        //return redirect()->back()->with('status', 'MaxScale server deleted.');
    }

    public function destroy($id)
    {
        Setting::find($id)->delete();
        //return redirect()->back()->with('status', 'MaxScale server deleted.');
    }
}

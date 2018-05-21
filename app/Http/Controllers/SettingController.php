<?php

namespace App\Http\Controllers;

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
        $setting = Setting::where('user_id', Auth::user()->id)->first();
        return view('setting.index', compact('setting'));
    }

    public function store(Request $request)
    {
        $settings = new Setting([
            'api_url' => $request->get('api_url'),
            'username' => $request->get('username'),
            'password' => bcrypt($request->get('password')),
            'user_id' => Auth::user()->id,
        ]);

        $settings->save();
        return redirect()->back()->with('status', 'Settings has been saved.');
    }

    public function edit($id)
    {
        $setting = Setting::find($id);
        return view('setting.index', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        Setting::find($id)->update($request->all());
        return redirect()->route('settings.index');
    }
}

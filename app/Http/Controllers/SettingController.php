<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSettingRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

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
            ->where('user_id', Auth::id())->get();
        return view('setting.index', compact('settings'));
    }

    public function store(StoreSettingRequest $request)
    {
        $settings = new Setting([
            'name'     => $request->get('api_name'),
            'api_url'  => $request->get('api_url'),
            'username' => $request->get('api_username'),
            'password' => Crypt::encrypt($request->get('api_password')),
            'user_id'  => Auth::id(),
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

    public function update(StoreSettingRequest $request, $id)
    {
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
        try {
            Setting::where('selected', 1)->where('user_id', Auth::id())->update(['selected' => false]);
            Setting::where('id', $id)->where('user_id', Auth::id())->update(['selected' => true]);
            Session::flash('success', 'Server selected.');
        } catch (\Exception) {
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
}


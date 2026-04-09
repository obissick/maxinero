<?php

namespace App\Http\Controllers;

use App\Services\MaxScaleClient;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class MaxscaleController extends Controller
{
    public function __construct(private MaxScaleClient $maxscale)
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $maxscale = json_decode($this->maxscale->get('maxscale'), true);
            $log      = $this->maxscale->get('maxscale/logs');

            return view('maxinfo', compact('maxscale', 'log'));
        } catch (ConnectException) {
            return redirect('settings')->with('error', 'Issue connecting to MaxScale backend.');
        } catch (\Exception $e) {
            return redirect('settings')->with('error', $e->getMessage());
        }
    }

    public function create() {}
    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}

    public function destroy(string $id)
    {
        Session::flash('success', 'Done.');

        return View::make('flash-message');
    }

    public function flush_log(Request $request)
    {
        $this->maxscale->post('maxscale/logs/flush', []);
        Session::flash('success', 'Log flushed.');

        return View::make('flash-message');
    }
}
<?php

namespace App\Http\Controllers;

use App\Services\MaxScaleClient;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(private MaxScaleClient $maxscale)
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $threads  = json_decode($this->maxscale->get('maxscale/threads'), true);
            $sessions = json_decode($this->maxscale->get('sessions'), true);

            return view('dash.view', [
                'count'         => count($sessions['data'] ?? []),
                'sessions'      => $sessions,
                'threads_count' => count($threads['data'] ?? []),
                'threads'       => $threads,
            ]);
        } catch (ConnectException) {
            return redirect('settings')->with('error', 'Issue connecting to MaxScale backend.');
        } catch (\Exception $e) {
            return redirect('settings')->with('error', $e->getMessage());
        }
    }
}

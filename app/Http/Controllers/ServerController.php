<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServerRequest;
use App\Services\MaxScaleClient;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class ServerController extends Controller
{
    public function __construct(private MaxScaleClient $maxscale)
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $maxserver = $this->maxscale->getApiInfo();
            $server_stat = DB::table('server_stats')
                ->select(DB::raw('created_at, sum(connections) AS sum, sum(active_operations) AS sum_ops'))
                ->where('setting_id', $maxserver->id)
                ->whereRaw("created_at > (CONVERT_TZ(NOW(), @@session.time_zone, '+00:00') - INTERVAL 1 HOUR)")
                ->groupBy('created_at')
                ->orderBy('created_at')
                ->get()->toArray();

            $times    = array_column($server_stat, 'created_at');
            $sum_conn = array_column($server_stat, 'sum');
            $sum_ops  = array_column($server_stat, 'sum_ops');

            $servers = json_decode($this->maxscale->get('servers'), true);

            return view('servers.servers', compact('servers'))
                ->with('times', json_encode($times, JSON_NUMERIC_CHECK))
                ->with('sum_conn', json_encode($sum_conn, JSON_NUMERIC_CHECK))
                ->with('sum_ops', json_encode($sum_ops, JSON_NUMERIC_CHECK));
        } catch (ConnectException) {
            return redirect('settings')->with('error', 'Issue connecting to MaxScale backend.');
        } catch (\Exception $e) {
            return redirect('settings')->with('error', $e->getMessage());
        }
    }

    public function create() {}

    public function store(StoreServerRequest $request)
    {
        $services      = array_filter(explode(',', trim($request->input('services', ''))));
        $relation_data = array_values(array_map(fn($s) => ['id' => $s, 'type' => 'services'], $services));

        $params = [
            'address' => $request->input('address'),
            'port'    => (int) $request->input('port'),
        ];
        if ($request->filled('protocol')) {
            $params['protocol'] = $request->input('protocol');
        }

        $data = [
            'data' => [
                'id'   => $request->input('server_id'),
                'type' => 'servers',
                'attributes' => ['parameters' => $params],
            ],
        ];

        if (! empty($relation_data)) {
            $data['data']['relationships'] = ['services' => ['data' => $relation_data]];
        }

        $this->maxscale->post('servers', $data);

        return $this->maxscale->get('servers/' . $request->input('server_id'));
    }

    public function show(string $id)
    {
        $server = json_decode($this->maxscale->get('servers/' . $id), true);

        return view('servers.serverdetail', compact('server'));
    }

    public function edit(string $id)
    {
        return $this->maxscale->get('servers/' . $id);
    }

    public function update(StoreServerRequest $request, string $id)
    {
        $services      = array_filter(explode(',', trim($request->input('services', ''))));
        $relation_data = array_values(array_map(fn($s) => ['id' => $s, 'type' => 'services'], $services));

        $params = [
            'address' => $request->input('address'),
            'port'    => (int) $request->input('port'),
        ];
        if ($request->filled('protocol')) {
            $params['protocol'] = $request->input('protocol');
        }

        $data = [
            'data' => [
                'id'   => $request->input('server_id'),
                'type' => 'servers',
                'attributes' => ['parameters' => $params],
            ],
        ];

        if (! empty($relation_data)) {
            $data['data']['relationships'] = ['services' => ['data' => $relation_data]];
        }

        $this->maxscale->patch('servers/' . $id, $data);

        return $this->maxscale->get('servers/' . $request->input('server_id'));
    }

    public function destroy(string $id)
    {
        $this->maxscale->delete('servers/' . preg_replace('#[ -]+#', '-', $id));
        Session::flash('success', 'Server deleted.');

        return View::make('flash-message');
    }

    public function change_state(Request $request, string $id)
    {
        $server = json_decode($this->maxscale->get('servers/' . $id), true);
        $state  = $request->input('state');
        $states = explode(',', trim($server['data']['attributes']['state']));

        if (in_array($state, $states)) {
            $this->maxscale->put('servers/' . $id . '/clear?state=' . $state);
        } else {
            $this->maxscale->put('servers/' . $id . '/set?state=' . $state . '&force=yes');
        }

        sleep(1);

        return $this->maxscale->get('servers/' . $id);
    }
}
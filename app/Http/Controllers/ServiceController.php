<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Services\MaxScaleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ServiceController extends Controller
{
    public function __construct(private MaxScaleClient $maxscale)
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $services = json_decode($this->maxscale->get('services'), true);
            $monitors = json_decode($this->maxscale->get('monitors'), true);

            return view('services.services', compact('services', 'monitors'));
        } catch (ConnectException) {
            return redirect('settings')->with('error', 'Issue connecting to MaxScale backend.');
        } catch (\Exception $e) {
            return redirect('settings')->with('error', $e->getMessage());
        }
    }

    public function create() {}

    public function store(StoreServiceRequest $request)
    {
        $data = [
            'data' => [
                'id'   => $request->input('service_id'),
                'type' => $request->input('service_type'),
                'attributes' => [
                    'router' => $request->input('module'),
                    'parameters' => [
                        'user'     => $request->input('user'),
                        'password' => $request->input('password'),
                    ],
                ],
            ],
        ];

        try {
            $this->maxscale->post('services/', $data);

            return $this->maxscale->get('services/' . $request->input('service_id'));
        } catch (ClientException $e) {
            return redirect('services')->with('error', $e->getResponse()->getBody(true));
        }
    }

    public function show(string $id)
    {
        $maxserver    = $this->maxscale->getApiInfo();
        $service_stat = DB::table('service_stats')
            ->select(DB::raw('created_at, avg(connections) AS avg'))
            ->where('setting_id', $maxserver->id)
            ->where('service_id', $id)
            ->whereRaw("created_at > (CONVERT_TZ(NOW(), @@session.time_zone, '+00:00') - INTERVAL 1 HOUR)")
            ->groupBy('created_at')
            ->groupBy('service_id')
            ->orderBy('created_at')
            ->orderBy('service_id')
            ->get()->toArray();

        $times    = array_column($service_stat, 'created_at');
        $avg_ctime = array_column($service_stat, 'avg');

        $service   = json_decode($this->maxscale->get('services/' . $id), true);
        $listeners = json_decode($this->maxscale->get('services/' . $id . '/listeners'), true);

        return view('services.servicedetail', compact('service', 'listeners'))
            ->with('times', json_encode($times, JSON_NUMERIC_CHECK))
            ->with('avg_ctime', json_encode($avg_ctime, JSON_NUMERIC_CHECK));
    }

    public function edit(string $id)
    {
        return $this->maxscale->get('services/' . $id);
    }

    public function update(StoreServiceRequest $request, string $id)
    {
        $data = [
            'data' => [
                'id'   => $request->input('service_id'),
                'type' => $request->input('service_type'),
                'attributes' => [
                    'router' => $request->input('module'),
                    'parameters' => [
                        'user'     => $request->input('user'),
                        'password' => $request->input('password'),
                    ],
                ],
            ],
        ];

        try {
            $this->maxscale->patch('services/' . $id, $data);

            return $this->maxscale->get('services/' . $request->input('service_id'));
        } catch (ClientException) {
            // silently handled
        }
    }

    public function destroy(string $id)
    {
        $this->maxscale->delete('services/' . preg_replace('#[ -]+#', '-', $id));
    }

    public function create_listener(Request $request, string $id)
    {
        $listener = $request->input('listener_id');
        $id       = preg_replace('#[ -]+#', '-', $id);

        $data = [
            'data' => [
                'id'   => $listener,
                'type' => $request->input('listener_type') ?: 'listeners',
                'attributes' => [
                    'parameters' => [
                        'port' => (int) $request->input('port'),
                    ],
                ],
            ],
        ];

        foreach ([
            'address'              => 'address',
            'protocol'             => 'protocol',
            'auth'                 => 'authenticator',
            'auth_options'         => 'authenticator_options',
            'ssl_key'              => 'ssl_key',
            'ssl_cert'             => 'ssl_cert',
            'ssl_ca_cert'          => 'ssl_ca_cert',
            'ssl_version'          => 'ssl_version',
            'ssl_depth'            => 'ssl_cert_verify_depth',
        ] as $input => $param) {
            if ($request->filled($input)) {
                $data['data']['attributes']['parameters'][$param] = $request->input($input);
            }
        }

        $this->maxscale->post('services/' . $id . '/listeners', $data);

        return $this->maxscale->get('services/' . $id . '/listeners/' . $listener);
    }

    public function destroy_listener(Request $request, string $id)
    {
        try {
            $listener = $request->input('listener');
            $id       = preg_replace('#[ -]+#', '-', $id);
            $this->maxscale->delete('services/' . $id . '/listeners/' . $listener);
        } catch (ClientException $e) {
            $errmessage = str_contains($e->getMessage(), 'was not created at runtime')
                ? 'Listener was not created at runtime. Remove listener manually.'
                : $e->getMessage();

            return response()->json(['error', $errmessage]);
        }
    }

    public function change_state(Request $request, string $id)
    {
        $this->maxscale->put('services/' . $id . '/' . $request->input('type'));

        return $this->maxscale->get('services/' . $id);
    }
}
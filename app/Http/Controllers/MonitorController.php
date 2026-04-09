<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMonitorRequest;
use App\Services\MaxScaleClient;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    public function __construct(private MaxScaleClient $maxscale)
    {
        $this->middleware('auth');
    }

    public function index() {}

    public function create() {}

    public function store(StoreMonitorRequest $request)
    {
        $data = $this->buildData($request);
        $this->maxscale->post('monitors', $data);

        return $this->maxscale->get('monitors/' . $request->input('monitor_id'));
    }

    public function show(string $id) {}

    public function edit(string $id)
    {
        return $this->maxscale->get('monitors/' . $id);
    }

    public function update(StoreMonitorRequest $request, string $id)
    {
        $data = $this->buildData($request);
        $this->maxscale->patch('monitors/' . $id, $data);

        return $this->maxscale->get('monitors/' . $request->input('monitor_id'));
    }

    public function destroy(string $id)
    {
        $this->maxscale->delete('monitors/' . preg_replace('#[ -]+#', '-', $id));
    }

    public function change_state(Request $request, string $id)
    {
        $this->maxscale->put('monitors/' . $id . '/' . $request->input('type'));

        return $this->maxscale->get('monitors/' . $id);
    }

    private function buildData(StoreMonitorRequest $request): array
    {
        $servers       = array_filter(explode(',', trim($request->input('servers', ''))));
        $relation_data = array_values(array_map(fn($s) => ['id' => $s, 'type' => 'servers'], $servers));

        $data = [
            'data' => [
                'id'   => $request->input('monitor_id'),
                'type' => $request->input('monitor_type'),
                'attributes' => [
                    'module' => $request->input('module'),
                    'parameters' => [
                        'monitor_interval' => (int) $request->input('monitor_interval'),
                        'user'             => $request->input('user'),
                        'password'         => $request->input('password'),
                    ],
                ],
            ],
        ];

        if (! empty($relation_data)) {
            $data['data']['relationships'] = ['servers' => ['data' => $relation_data]];
        }

        return $data;
    }
}
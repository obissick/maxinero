<?php

namespace App\Jobs;

use App\ServerStats;
use App\ServiceStats;
use App\Setting;
use App\Services\MaxScaleClient;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(MaxScaleClient $client): void
    {
        foreach (Setting::all() as $setting) {
            try {
                $serverStats  = json_decode($client->getForSetting($setting, 'servers'), true);
                $serviceStats = json_decode($client->getForSetting($setting, 'services'), true);

                foreach ($serverStats['data'] as $item) {
                    ServerStats::create([
                        'setting_id'        => $setting->id,
                        'server_id'         => $item['id'],
                        'connections'       => $item['attributes']['statistics']['connections'],
                        'total_connections' => $item['attributes']['statistics']['total_connections'],
                        'active_operations' => $item['attributes']['statistics']['active_operations'],
                    ]);
                }

                foreach ($serviceStats['data'] as $item) {
                    if (($item['attributes']['router'] ?? '') === 'cli') {
                        continue;
                    }

                    ServiceStats::create([
                        'setting_id'        => $setting->id,
                        'service_id'        => $item['id'],
                        'connections'       => $item['attributes']['connections'] ?? $item['attributes']['statistics']['current_connections'] ?? 0,
                        'total_connections' => $item['attributes']['total_connections'] ?? $item['attributes']['statistics']['total_connections'] ?? 0,
                        'queries'           => $item['attributes']['router_diagnostics']['queries'] ?? null,
                    ]);
                }
            } catch (ConnectException) {
                // Skip unreachable servers; logged by the queue worker
            }
        }
    }
}

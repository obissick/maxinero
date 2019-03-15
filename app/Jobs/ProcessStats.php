<?php

namespace App\Jobs;

use App\ServerStats;
use App\ServiceStats;
use App\Setting as Setting;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class ProcessStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $setting;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $max_servers = Setting::all();
        foreach($max_servers as $max_server) {
            try{
                $server_stats = json_decode($this->get_stats($max_server,'servers'), true);
                $service_stats = json_decode($this->get_stats($max_server,'services'), true);
                for($i = 0; $i < count($server_stats['data']); $i++){
                    $stat = new ServerStats;
                    $stat->setting_id = $max_server->id;
                    $stat->server_id = $server_stats['data'][$i]['id'];
                    $stat->connections = $server_stats['data'][$i]['attributes']['statistics']['connections'];
                    $stat->total_connections = $server_stats['data'][$i]['attributes']['statistics']['total_connections'];
                    $stat->active_operations = $server_stats['data'][$i]['attributes']['statistics']['active_operations'];
                    $stat->save();
                }
                for($i = 0; $i < count($service_stats['data']); $i++){
                    $stat = new ServiceStats;
                    $stat->setting_id = $max_server->id;
                    $stat->service_id = $service_stats['data'][$i]['id'];
                    $stat->connections = $service_stats['data'][$i]['attributes']['connections'];
                    $stat->total_connections = $service_stats['data'][$i]['attributes']['total_connections'];
                    if($service_stats['data'][$i]['attributes']['router'] === "cli"){

                    }else{
                        $stat->queries = $service_stats['data'][$i]['attributes']['router_diagnostics']['queries'];
                    }
                    
                    $stat->save();
                }
            }catch (\GuzzleHttp\Exception\ConnectException $exception){
                print($exception->getResponse());
            }
            
            
        }
    }

    function get_stats($server, $location){
        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', $server->api_url.$location, [
            'auth' => [$server->username, Crypt::decrypt($server->password)], 
            'verify' => false
        ]);
        return $res->getBody()->getContents();
    }
}

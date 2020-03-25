@extends('layouts.app')

@section('content')
<div class="container container-fluid">
    <div class="flash-message"></div>
    <div class="float-right">
        <button 
            type="button" 
            class="btn btn-primary" 
            data-toggle="modal" 
            data-target="#favoritesModal">
        Log Info
        </button>
    </div>
    <h2>MaxScale</h2>
    <div class="row">
         
        <div class="table-responsive">
                
            <!-- Table-to-load-the-data Part -->
            <table class="table table-borderless table-sm">
                <tr>
                    <th>Version</th>
                    <td>{{$maxscale['data']['attributes']['version']}}</td>
                </tr>
                <tr>
                    <th>Commit</th>
                    <td>{{$maxscale['data']['attributes']['commit']}}</td>
                </tr>
                <tr>
                    <th>Started</th>
                    <td>{{$maxscale['data']['attributes']['started_at']}}</td>
                </tr>
                <tr>
                    <th>Activated</th>
                    <td>{{$maxscale['data']['attributes']['activated_at']}}</td>
                </tr>
                <tr>
                    <th>Uptime</th>
                    <td>{{$maxscale['data']['attributes']['uptime']}}</td>
                </tr>
                <tr>
                    <th>Library Directory</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['libdir']}}</td>
                </tr>
                <tr>
                    <th>Data Directory</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['datadir']}}</td>
                </tr>
                <tr>
                    <th>Process Directory</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['process_datadir']}}</td>
                </tr>
                <tr>
                    <th>Cache Directory</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['cachedir']}}</td>
                </tr>
                <tr>
                    <th>Config Directory</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['configdir']}}</td>
                </tr>
                <tr>
                    <th>Config Persist Directory</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['config_persistdir']}}</td>
                </tr>
                <tr>
                    <th>Config Module Directory</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['module_configdir']}}</td>
                </tr>
                <tr>
                    <th>PID Directory</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['piddir']}}</td>
                </tr>
                <tr>
                    <th>Log Directory</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['logdir']}}</td>
                </tr>
                <tr>
                    <th>Lang Directory</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['langdir']}}</td>
                </tr>
                <tr>
                    <th>Exec Directory</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['execdir']}}</td>
                </tr>
                <tr>
                    <th>Connector Plugin Directory</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['connector_plugindir']}}</td>
                </tr>
                <tr>
                    <th>Threads</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['threads']}}</td>
                </tr>
                <tr>
                    <th>Thread Stack Size</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['thread_stack_size']}}</td>
                </tr>
                <tr>
                    <th>Auth Connect Timeout</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['auth_connect_timeout']}}</td>
                </tr>
                <tr>
                    <th>Auth Read Timeout</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['auth_read_timeout']}}</td>
                </tr>
                <tr>
                    <th>Auth Write Timeout</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['auth_write_timeout']}}</td>
                </tr>
                <tr>
                    <th>Skip Permission Checks</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['skip_permission_checks'] ? 'true' : 'false'}}</td>
                </tr>
                <tr>
                    <th>Admin Auth</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['admin_auth'] ? 'true' : 'false'}}</td>
                </tr>
                <tr>
                    <th>Admin Enabled</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['admin_enabled'] ? 'true' : 'false'}}</td>
                </tr>
                <tr>
                    <th>Admin Log Auth Failures</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['admin_log_auth_failures'] ? 'true' : 'false'}}</td>
                </tr>
                <tr>
                    <th>Admin Host</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['admin_host']}}</td>
                </tr>
                <tr>
                    <th>Admin Port</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['admin_port']}}</td>
                </tr>
                <tr>
                    <th>Admin SSL Key</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['admin_ssl_key']}}</td>
                </tr>
                <tr>
                    <th>Admin SSL Cert</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['admin_ssl_cert']}}</td>
                </tr>
                <tr>
                    <th>Admin SSL CA Cert</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['admin_ssl_ca_cert']}}</td>
                </tr>
                <tr>
                    <th>Passive</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['passive'] ? 'true' : 'false'}}</td>
                </tr>
                <tr>
                    <th>Query Classifier</th>
                    <td>{{$maxscale['data']['attributes']['parameters']['query_classifier']}}</td>
                </tr>
            </table>
        </div>
    </div> 
    <div class="modal fade" id="favoritesModal" 
     tabindex="-1" role="dialog" 
     aria-labelledby="favoritesModalLabel">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" 
                id="favoritesModalLabel">Log Info</h4>
                <button type="button" class="close" 
                data-dismiss="modal" 
                aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <pre class="prettyprint lang-json">
                    <code >
                        {{$log}}
                    </code>
                </pre>
            </div>
            <div class="modal-footer">
                <button type="button" 
                class="btn btn-default" 
                data-dismiss="modal">Close</button>
                <span class="pull-right">
                <button type="button" class="btn btn-primary" name="flush" id="flush">
                    Flush & Rotate
                </button>
                </span>
            </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/gh/google/code-prettify@master/loader/run_prettify.js?autoload=true&amp;skin=sunburst&amp;lang=css" defer></script>
@endsection
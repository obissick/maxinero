@extends('layouts.app')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script>
	
	var times =  {!! $times !!}.map(function(e) {
   		return e.created_at;
    });
    
    var times = {!! $times !!};
	var avg_ctime = {!! $avg_ctime !!}.map(function(e) {
   		return e.avg_ctime;
	});
    var avg_ctime = {!! $avg_ctime !!}
	var config = {
		type: 'line',
		data: {
            labels: times,
            datasets: [{ 
                data: avg_ctime,
                label: "Connections",
                borderColor: "#3e95cd",
                fill: false
            }
            ]
        },
		options: {
			responsive: true,
			legend: {
				position: 'top',
			},
			title: {
				display: false,
				text: 'Goals'
			},
			animation: {
				animateScale: true,
				animateRotate: true
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true, 
                        stepSize: 1
                    }
                }]
            }
		}
	};

	window.onload = function() {
		var ctx = document.getElementById('line').getContext('2d');
		window.myDoughnut = new Chart(ctx, config);
	};
</script>
<div class="container container-fluid">
    <div class="flash-message"></div>
    <h2>{{$service['data']['id']}}</h2>
    <canvas id="line" height="150" width="600"></canvas>
    <div class="row">
            <div class="table-responsive">
                <!-- Table-to-load-the-data Part -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Router</th>
                            <th>State</th>
                            <th>Total Connections</th>
                            <th>Connections</th>
                            <th>Started</th>
                            <th>Queries</th>
                            <th>Servers</th>
                        </tr>
                    </thead>
                    <tbody id="services-list" name="services-list">
                    
                        <tr id="service{{$service['data']['id']}}">
                            <td>{{$service['data']['attributes']['router']}}</td>
                            <td>{{$service['data']['attributes']['state']}}</td>
                            <td>{{$service['data']['attributes']['total_connections']}}</td>
                            <td>{{$service['data']['attributes']['connections']}}</td>
                            <td>{{$service['data']['attributes']['started']}}</td>
                            @if($service['data']['attributes']['router'] === "cli")
                            @else
                                <td>{{$service['data']['attributes']['router_diagnostics']['queries']}}</td>
                            @endif
                            <td>
                                @isset($service['data']['relationships']['servers']['data'])
                                    @for($y = 0; $y < count($service['data']['relationships']['servers']['data']); $y++)
                                        <button class="btn btn-primary btn-sm" style="pointer-events: none;" type="button" disabled>{{$service['data']['relationships']['servers']['data'][$y]['id']}}</button>
                                    @endfor
                                @endisset
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
                
    </div>

    <h2>Listeners</h2>
    <div id="listener-button">
        <button id="add-listener-button" name="add-listener-button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#listener">Add Listener</button>
    </div>
        <div class="row">
            <div class="table-responsive">
                    <!-- Table-to-load-the-data Part -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Port</th>
                                <th>Protocol</th>
                                <th>Authenticator</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="listeners-list" name="listeners-list">
                            @for ($i = 0; $i < count($listeners['data']); $i++)
                            <tr id="listener{{$listeners['data'][$i]['id']}}">
                                <td>{{$listeners['data'][$i]['id']}}</td>
                                <td>{{$listeners['data'][$i]['type']}}</td>
                                <td>{{$listeners['data'][$i]['attributes']['parameters']['port']}}</td>
                                <td>{{$listeners['data'][$i]['attributes']['parameters']['protocol']}}</td>
                                <td>{{$listeners['data'][$i]['attributes']['parameters']['authenticator']}}</td>
                                <td id="delete-col">
                                    <button class="btn btn-danger btn-xs btn-delete delete-listener" value="{{$listeners['data'][$i]['id']}}">Delete</button>
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            <!-- End of Table-to-load-the-data Part -->
            <!-- Modal (Pop up when detail button clicked) -->
            <div class="modal fade" id="listener" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title" id="myModalLabel">Listener Editor</h4>
                        </div>
                        <div class="modal-body">
                            <form id="addlistner" name="addlistener" class="form-horizontal" novalidate="">
                                {{ csrf_field() }}
                                <div class="form-group error">
                                    <label for="listener_id" class="col-sm-3 control-label">Listener Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control has-error" id="listener_id" name="listener_id" placeholder="Name" value="">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="listener_type" class="col-sm-3 control-label">Listener Type</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="listener_type" name="listener_type" placeholder="listeners" value="">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="address" class="col-sm-3 control-label">Address</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Optional" value="">
                                    </div>
                                </div>

                                <div class="form-group">
                                        <label for="port" class="col-sm-3 control-label">Port</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="port" name="port" placeholder="3306" value="">
                                        </div>
                                </div>
                                <div class="form-group">
                                        <label for="protocol" class="col-sm-3 control-label">Protocol</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="protocol" name="protocol" placeholder="Optional" value="">
                                        </div>
                                </div>
                                <div class="form-group">
                                    <label for="auth" class="col-sm-3 control-label">Authenticator</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="auth" name="auth" placeholder="Optional" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="auth_options" class="col-sm-3 control-label">Authenticator Options</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="auth_options" name="auth_options" placeholder="Optional" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ssl_key" class="col-sm-3 control-label">SSL Key</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="ssl_key" name="ssl_key" placeholder="Optional" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ssl_cert" class="col-sm-3 control-label">SSL Cert</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="ssl_cert" name="ssl_cert" placeholder="Optional" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ssl_ca_cert" class="col-sm-3 control-label">SSL CA Cert</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="ssl_ca_cert" name="ssl_ca_cert" placeholder="Optional" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ssl_version" class="col-sm-3 control-label">SSL Version</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="ssl_version" name="ssl_version" placeholder="Optional" value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ssl_cert_verify_depth" class="col-sm-3 control-label">SSL Cert Verify Depth</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="ssl_depth" name="ssl_depth" placeholder="Optional" value="">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" id="add-listener" value="add">Save changes</button>
                                        <input type="hidden" id="service_id" name="service_id" value="{{$service['data']['id']}}">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

@endsection
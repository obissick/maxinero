@extends('layouts.app')

@section('content')
<div class="container container-fluid">
    <div class="row">
        
        <button id="btn-add" name="btn-add" class="btn btn-success btn-xs" data-toggle="modal" data-target="#server">Add Service</button>
            <div>
                <!-- Table-to-load-the-data Part -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Router</th>
                            <th>State</th>
                            <th>Total Connections</th>
                            <th>Connections</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="servers-list" name="servers-list">
                        @for ($i = 0; $i < count($services['data']); $i++)
                        <tr id="server{{$services['data'][$i]['id']}}">
                            <td>{{$services['data'][$i]['id']}}</td>
                            <td>{{$services['data'][$i]['attributes']['router']}}</td>
                            <td>{{$services['data'][$i]['attributes']['state']}}</td>
                            <td>{{$services['data'][$i]['attributes']['total_connections']}}</td>
                            <td>{{$services['data'][$i]['attributes']['connections']}}</td>
                            <td>
                                @if($services['data'][$i]['attributes']['state'] === "Started")
                                    <button class="btn btn-danger btn-xs btn-detail stop-service" value="{{$services['data'][$i]['id']}}">Stop</button>  
                                @else
                                    <button class="btn btn-success btn-xs btn-detail start-service" value="{{$services['data'][$i]['id']}}">Start</button>
                                @endif
                                <button class="btn btn-warning btn-xs btn-detail open-modal" value="{{$services['data'][$i]['id']}}">Edit</button>
                                <button class="btn btn-danger btn-xs btn-delete delete-server" value="{{$services['data'][$i]['id']}}">Delete</button>
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
                <!-- End of Table-to-load-the-data Part -->
                <!-- Modal (Pop up when detail button clicked) -->
                <div class="modal fade" id="service" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <h4 class="modal-title" id="myModalLabel">Server Editor</h4>
                            </div>
                            <div class="modal-body">
                                <form id="addservice" name="addservice" class="form-horizontal" novalidate="" method="POST" action="{{ route('services.store') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group error">
                                        <label for="service_id" class="col-sm-3 control-label">Service ID</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control has-error" id="service_id" name="service_id" placeholder="ID" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="address" class="col-sm-3 control-label">Address</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="address" name="address" placeholder="0.0.0.0" value="">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="port" class="col-sm-3 control-label">Port</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="port" name="port" placeholder="3306" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                            <label for="port" class="col-sm-3 control-label">Protocol</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="protocol" name="protocol" placeholder="MariaDBBackend" value="">
                                            </div>
                                    </div>
                                    <div class="form-group">
                                            <label for="port" class="col-sm-3 control-label">Services</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="services" name="services" placeholder="Separate by comma" value="">
                                            </div>
                                    </div>
                                    <div class="form-group">
                                            <label for="port" class="col-sm-3 control-label">Monitors</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="monitors" name="monitors" placeholder="Separate by comma" value="">
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary" id="btn-save">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
</div>

@endsection
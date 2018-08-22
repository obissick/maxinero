@extends('layouts.app')

@section('content')
<div class="container container-fluid">
    <h2>DB Servers</h2>
    <button id="btn-add" name="btn-add" class="btn btn-success btn-xs">Add Server</button>
    <div class="row">
        <div class="table-responsive-sm">
            <br />
            <!-- Table-to-load-the-data Part -->
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Address</th>
                        <th>Port</th>
                        <th>Protocol</th>
                        <th>State</th>
                        <th>Connections</th>
                        <th>Total Connections</th>
                        <th>Actions</th>

                    </tr>
                </thead>
                <tbody id="servers-list" name="servers-list">
                    @for ($i = 0; $i < count($servers['data']); $i++)
                    <tr id="server{{$servers['data'][$i]['id']}}">
                        <td>{{$servers['data'][$i]['id']}}</td>
                        <td>{{$servers['data'][$i]['attributes']['parameters']['address']}}</td>
                        <td>{{$servers['data'][$i]['attributes']['parameters']['port']}}</td>
                        <td>{{$servers['data'][$i]['attributes']['parameters']['protocol']}}</td>
                        <td>{{$servers['data'][$i]['attributes']['state']}}</td>
                        <td>{{$servers['data'][$i]['attributes']['statistics']['connections']}}</td>
                        <td>{{$servers['data'][$i]['attributes']['statistics']['total_connections']}}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-primary btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    State
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li><button type="button" class="btn btn-link btn-xs master" value="{{$servers['data'][$i]['id']}}">master</button></li>
                                    <li><button type="button" class="btn btn-link btn-xs slave" value="{{$servers['data'][$i]['id']}}">slave</button></li>
                                    <li><button type="button" class="btn btn-link btn-xs maintenance" value="{{$servers['data'][$i]['id']}}">maintenance</button></li>
                                    <li><button type="button" class="btn btn-link btn-xs running" value="{{$servers['data'][$i]['id']}}">running</button></li>
                                    <li><button type="button" class="btn btn-link btn-xs synced" value="{{$servers['data'][$i]['id']}}">synced</button></li>
                                    <li><button type="button" class="btn btn-link btn-xs ndb" value="{{$servers['data'][$i]['id']}}">ndb</button></li>
                                    <li><button type="button" class="btn btn-link btn-xs stale" value="{{$servers['data'][$i]['id']}}">stale</button></li>
                                </ul>
                                <button class="btn btn-warning btn-xs btn-detail open-modal" value="{{$servers['data'][$i]['id']}}">Edit</button>
                                <button class="btn btn-danger btn-xs btn-delete delete-server" value="{{$servers['data'][$i]['id']}}">Delete</button>
                            </div>
                            
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
                <!-- End of Table-to-load-the-data Part -->
                <!-- Modal (Pop up when detail button clicked) -->
                <div class="modal fade" id="server" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <h4 class="modal-title" id="myModalLabel">Server Editor</h4>
                            </div>
                            <div class="modal-body">
                                <form id="addserver" name="addserver" class="form-horizontal" novalidate="">
                                    {{ csrf_field() }}
                                    <div class="form-group error">
                                        <label for="server_id" class="col-sm-3 control-label">Server ID</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control has-error" id="server_id" name="server_id" placeholder="ID" value="">
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
                                            <button type="button" class="btn btn-primary" id="btn-save" value="add">Save changes</button>
                                            <input type="hidden" id="server_id" name="server_id" value="0">
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
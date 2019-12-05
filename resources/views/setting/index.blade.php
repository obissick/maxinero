@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="flash-message"></div>
            <div class="panel panel-default">
                <h2>MaxScale Servers</h2>
                <button id="add-maxscale" name="add-maxscale" class="btn btn-success btn-xs" data-toggle="modal" data-target="#config">Add API</button>
                <div class="panel-body">
                    @if (empty($settings))
                        <p>Add a Maxscale server to begin.</p>
                    @endif
                    <br />
                    <table class="table" id="apis-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>API URL</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="configs-list" name="configs-list">
                            @foreach ($settings as $setting)
                            <tr id="setting{{$setting->id}}">
                                <td id="name{{$setting->name}}">{{$setting->name}}</td>
                                <td id="url{{$setting->api_url}}">{{$setting->api_url}}</td>
                                <td id="username{{$setting->username}}">{{$setting->username}}</td>
                                <td></td>
                                <td>
                                    @if($setting->selected)
                                          
                                    @else
                                        <button class="btn btn-success btn-xs btn-detail select" value="{{$setting->id}}">Select</button>
                                    @endif
                                    <button class="btn btn-info btn-xs btn-detail edit-maxscale" value="{{$setting->id}}">Edit</button>
                                    <button class="btn btn-danger btn-xs btn-delete delete-maxscale" value="{{$setting->id}}">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="modal fade" id="config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">MaxScale Editor</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>    
                                </div>
                                <div class="modal-body">
                                    <form id="add-api-form" name="add-api-form" class="form-horizontal" novalidate="">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label for="api_name" class="col-sm-3 control-label">Name</label>
                                            <div class="col-sm-9">
                                                <input type="hidden" name="setting_id" id="setting_id"/>
                                                <input type="text" class="form-control has-error" id="api_name" name="api_name" placeholder="" value="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="api_url" class="col-sm-3 control-label">API URL</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control has-error" id="api_url" name="api_url" placeholder="https://host:port/v1/" value="">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="api_username" class="col-sm-3 control-label">Username</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="api_username" name="api_username" placeholder="" value="">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="api_password" class="col-sm-3 control-label">Password</label>
                                            <div class="col-sm-9">
                                                <input type="password" class="form-control" id="api_password" name="api_password" placeholder="" value="">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary" name= "add-api" id="add-api" value="add">Save changes</button>
                                            <input type="hidden" id="api_id" name="api_id" value="0">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
        $(document).ready(function() {
            var table = $('#apis-table').DataTable();
        } );
    </script>
@endsection
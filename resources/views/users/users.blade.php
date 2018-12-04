@extends('layouts.app')

@section('content')
<div class="container container-fluid">
    <div class="flash-message"></div>
    <h2>Users</h2>
    <div id="button-user">
        <button id="add-user-button" name="add-user-button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#user">Add User</button>
    </div>
    <div class="row">
        <div class="table-responsive-sm">
            <br />
            <!-- Table-to-load-the-data Part -->
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Account</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="users-list" name="users-list">
                    @for ($i = 0; $i < count($users['data']); $i++)
                    <tr id="user{{$users['data'][$i]['id']}}">
                        <td>{{$users['data'][$i]['id']}}</td>
                        <td>{{$users['data'][$i]['type']}}</td>
                        <td>{{$users['data'][$i]['attributes']['account']}}</td>
                        <td>
                            @if($users['data'][$i]['type'] === "inet")
                                <button class="btn btn-danger btn-xs btn-delete delete-user" name="delete-user" value="{{$users['data'][$i]['id']}}">Delete</button> 
                            @else
                                <button class="btn btn-warning btn-xs btn-disable disable-user" name="disable-user" value="{{$users['data'][$i]['id']}}">Disable</button>
                            @endif
                            
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
                <!-- End of Table-to-load-the-data Part -->
                <!-- Modal (Pop up when detail button clicked) -->
                <div class="modal fade" id="user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <h4 class="modal-title" id="myModalLabel">User Editor</h4>
                            </div>
                            <div class="modal-body">
                                <form id="adduser" name="adduser" class="form-horizontal" novalidate="">
                                    {{ csrf_field() }}
                                    <div class="form-group error">
                                        <label for="user_id" class="col-sm-3 control-label">User ID</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control has-error" id="user_id" name="user_id" placeholder="ID" value="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="col-sm-3 control-label">Password</label>
                                        <div class="col-sm-9">
                                            <input type="password" class="form-control" id="password" name="address" placeholder="Password" value="">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="account" class="col-sm-3 control-label">Account</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="account" name="account" placeholder="admin or basic" value="">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" id="add-user" value="add">Save changes</button>
                                            <input type="hidden" id="user" name="user" value="">
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
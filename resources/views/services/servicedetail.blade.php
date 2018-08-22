@extends('layouts.app')

@section('content')
<div class="container container-fluid">
    <h2>{{$service['data']['id']}}</h2>
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
                        </tr>
                        
                    </tbody>
                </table>
            </div>
                
    </div>

    <h2>Listeners</h2>
        <div class="row">
            <button id="add-listener" name="add-listener" class="btn btn-success btn-xs" data-toggle="listener-modal" data-target="#listener">Add Listener</button>
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
                        <tbody id="monitors-list" name="monitors-list">
                            @for ($i = 0; $i < count($listeners['data']); $i++)
                            <tr id="monitor{{$listeners['data'][$i]['id']}}">
                                <td>{{$listeners['data'][$i]['id']}}</td>
                                <td>{{$listeners['data'][$i]['type']}}</td>
                                <td>{{$listeners['data'][$i]['attributes']['parameters']['port']}}</td>
                                <td>{{$listeners['data'][$i]['attributes']['parameters']['protocol']}}</td>
                                <td>{{$listeners['data'][$i]['attributes']['parameters']['authenticator']}}</td>
                                <td>
                                    <button class="btn btn-danger btn-xs btn-delete delete-listener" value="{{$service['data']['id']}}.'/'.{{$listeners['data'][$i]['id']}}">Delete</button>
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
        </div>
</div>

@endsection
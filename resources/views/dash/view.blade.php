@extends('layouts.app')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

<div class="container container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"># Sessions</div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="numberCircle">{{$count}}</div>	
                    <br />
                    <div class="table-responsive">
                    <table class="table table-striped table-bordered task-table">
						
                        <!-- Table Headings -->
                        <thead>
                            <th>ID</th>
                            <th>User</th>
                            <th>Remote</th>
                            <th>Connected</th>
                            <th>Idle</th>
                        </thead>

                        <!-- Table Body -->
                        <tbody>
                            
                            @for($i = 0; $i < count($sessions['data']); $i++)
                                <tr>
                                    <!-- Task Name -->
                                    <td class="table-text">
                                        <a class="btn btn-sm btn-info" role="button" >{{ $sessions['data'][$i]['id']}}</a>
                                    </td>
                                    <td class="table-text">
                                        {{ $sessions['data'][$i]['attributes']['user'] }}
                                    </td>
                                    <td class="table-text">
                                        {{ $sessions['data'][$i]['attributes']['remote'] }}
                                    </td>
                                    <td class="table-text">
                                        {{ $sessions['data'][$i]['attributes']['connected'] }}
                                    </td>
                                    <td class="table-text">
                                        {{ $sessions['data'][$i]['attributes']['idle'] }}
                                    </td>
                                </tr>
                            @endfor
                            
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading"></div>
				<div class="panel-body">
					<canvas id="pie" height="280" width="600"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection


@extends('layouts.app')

@section('content')
<div class="container container-fluid">
    @include('flash-message')
    <div class="row">
  		<div class="col-sm-10"><h1>{{$user->name}}</h1></div>
    </div>
    <div class="row">
  		<div class="col-sm-4"><!--left col-->
              
          <ul class="list-group">
            <li class="list-group-item text-muted">Profile</li>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Joined:</strong></span> {{$user->created_at}}</li>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Last Updated:</strong></span> {{$user->updated_at}}</li>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Email:</strong></span> {{$user->email}}</li>
            
          </ul> 
          
          <ul class="list-group">
            <li class="list-group-item text-muted">Activity <i class="fa fa-dashboard fa-1x"></i></li>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Maxscale Servers: </strong></span>{{$apis->count}}</li>
          </ul> 
          
        </div><!--/col-3-->
    	<div class="col-sm-8">
          
          <ul class="nav nav-tabs" id="myTab">
            <!--<li class="active"><a href="#settings" data-toggle="tab">Settings</a></li>-->
          </ul>
              
          <div class="tab-content">
             <div class="tab-pane active" id="settings">
                  
                  <form class="form" id="updateForm" name="updateForm" method="POST" action="{{ route('profile.update', $user->id) }}" novalidate="">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="first_name"><h4>Name</h4></label>
                              <input type="text" class="form-control" name="name" id="name" value="{{$user->name}}" placeholder="Name" title="enter your name if any.">
                          </div>
                      </div>

                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="email"><h4>Email</h4></label>
                              <input type="email" class="form-control" name="email" id="email" value="{{$user->email}}" placeholder="you@email.com" title="enter your email.">
                          </div>
                      </div>

                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="password"><h4>Password</h4></label>
                              <input type="password" class="form-control" name="password" id="password" placeholder="password" title="enter your password.">
                          </div>
                      </div>
            
                      <div class="form-group">
                           <div class="col-xs-12">
                                <br>
                              	<button class="btn btn-lg btn-success" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                            </div>
                      </div>
              	</form>
              </div>
               
              </div><!--/tab-pane-->
          </div><!--/tab-content-->

        </div><!--/col-9-->
    </div><!--/row-->

@endsection
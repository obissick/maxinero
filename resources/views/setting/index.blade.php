@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Settings</div>
                <div class="panel-body">
                    @if (!empty($setting))

                        <form class="form-horizontal" method="POST" action="{{ route('settings.update', $setting->id) }}">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}
                            <div class="form-group{{ $errors->has('api_url') ? ' has-error' : '' }}">
                                <label for="title" class="col-md-4 control-label">API URL</label>

                                <div class="col-md-6">
                                    <input id="api_url" type="text" class="form-control" name="api_url" value="{{ $setting->api_url }}" autofocus>

                                    @if ($errors->has('api_url'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('api_url') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <label for="content" class="col-md-4 control-label">Username</label>

                                <div class="col-md-6">
                                    <input id="username" type="text" class="form-control" name="username" value="{{$setting->username}}" >

                                    @if ($errors->has('username'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" value="">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-success">
                                        Save
                                    </button>
                                    <a href="{{ URL::previous() }}" class="btn btn-danger">Cancel</a>
                                </div>
                            </div>
                        </form>
                    @else
                    <form class="form-horizontal" method="POST" action="{{ route('settings.store') }}">
                            {{ csrf_field() }}
                            
                            <div class="form-group{{ $errors->has('api_url') ? ' has-error' : '' }}">
                                <label for="title" class="col-md-4 control-label">API URL</label>

                                <div class="col-md-6">
                                    <input id="api_url" type="text" class="form-control" name="api_url" value="" autofocus>

                                    @if ($errors->has('api_url'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('api_url') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <label for="content" class="col-md-4 control-label">Username</label>

                                <div class="col-md-6">
                                    <input id="username" type="text" class="form-control" name="username" value="" >

                                    @if ($errors->has('username'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" value="">

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-success">
                                        Save
                                    </button>
                                    <a href="{{ URL::previous() }}" class="btn btn-danger">Cancel</a>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                
                <div class="panel-heading lg">Account Settings</div>

                <div class="panel-body">

                    <h3>Update User Information:</h3>
                    <div class="panel-body well">
                        <form class="form-horizontal" action="{{ url('account') }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Name:</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" id="new-name" class="form-control" value="{{ $user->name }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">Email:</label>
                                <div class="col-sm-10">
                                    <input type="email" name="email" id="new-email" class="form-control" value="{{ $user->email }}">
                                </div>
                            </div>
                            <h4>Confirm password:</h4>
                            <div class="form-group">
                                <label for="password" class="col-sm-2 control-label">Password:</label>
                                <div class="col-sm-10">
                                    <input type="password" name="password" id="password" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-8">
                                    <button type="submit" name="submit_type" value="update" class="btn btn-primary">
                                        Submit
                                    </button>
                                    <button type="submit" name="submit_type" value="cancel" class="btn btn-danger">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <h3>Change Password:</h3>
                    <div class="panel-body well">
                        <form class="form-horizontal" action="{{ url('account') }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('POST') }}
                            <h4>Old password:</h4>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Password:</label>
                                <div class="col-sm-10">
                                    <input type="password" name="password" id="oldpass" class="form-control">
                                </div>
                            </div>
                            <h4>New password:</h4>
                            <p>Must be 8-64 characters</p>
                            <div class="form-group">
                                <label for="password" class="col-sm-2 control-label">Password:</label>
                                <div class="col-sm-10">
                                    <input type="password" name="newpassword" id="newpass" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-sm-2 control-label">Confirm:</label>
                                <div class="col-sm-10">
                                    <input type="password" name="newpassword_confirmation" id="confpass" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-8">
                                    <button type="submit" name="submit_type" value="changepw" class="btn btn-primary">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

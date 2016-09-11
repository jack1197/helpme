@extends('layouts.app')

@section('content')
<script src="{{ url('/js/student.js') }}"></script>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>Student access</h2></div>

                <div class="panel-body">
                    @if (Auth::guest() )
                    Not logged in.
                    @else
                    Hello, {{ Auth::user()->name }}!
                    @endif
                    <!-- EXISTING CLASS -->
                    <div id="pan_join_class">
                        <h3>Join Class:</h3>
                        <div class="panel-body well">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="studentjoincode" class="col-sm-2 control-label">Student Join Code:</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="studentjoincode" id="studentjoincode" class="form-control input-lg">
                                        </div>
                                    </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Name:</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="name" id="studentname" class="form-control input" value={{Auth::check() ? Auth::user()->name : '' }}>
                                        </input>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="desk" class="col-sm-2 control-label">Desk/Location:</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="desk" id="studentdesk" class="form-control input">
                                        </input>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-8">
                                        <button id="btn_join_class" class="btn btn-primary">
                                            Join Class
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ACTIVE -->
                    <div id="pan_active_class">
                        <h3>Currently in class '<span id='curr_class'></span>'</h3>
                        <div class="panel-body well">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Name:</label>
                                    <div class="col-sm-10">
                                    <h4 id='curr_name'></h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="desk" class="col-sm-2 control-label">Desk/Location:</label>
                                    <div class="col-sm-10">
                                    <h4 id='curr_desk'></h4>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="reason" class="col-sm-2 control-label">Reason:</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="reason" id="helpreason" class="form-control input-lg">
                                        </input>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-8">
                                        <button id="btn_help_me" class="btn-lg btn-success">
                                            Help Me!
                                        </button>
                                        <button id="btn_scrub_that" class="btn-lg btn-primary">
                                            Oh, Nevermind.
                                        </button>
                                        <button id="btn_leave_class" class="btn-lg btn-danger">
                                            Leave Class
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

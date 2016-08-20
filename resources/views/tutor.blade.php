@extends('layouts.app')

@section('content')
<script src="{{ url('/js/tutor.js') }}">
</script>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>Tutor access</h2></div>
                <div class="panel-body">
                    @if (Auth::guest() )
                    Not logged in.
                    @else
                    Hello, {{ Auth::user()->name }}!
                    @endif
                    <!-- NEW CLASS -->
                    <div id="pan_make_class">
                        <h3>Create New Class:</h3>
                        <div class="panel-body well">
                            <div class="form-horizontal">
                                <h4>Class information:</h4>
                                <div class="form-group">
                                    <label for="classname" class="col-sm-2 control-label">Name:</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="classname" id="newclassname" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="classroom" class="col-sm-2 control-label">Room:</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="classroom" id="newclassroom" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-8">
                                        <button id="btn_make_class" class="btn btn-primary">
                                            Make Class
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- EXISTING CLASS -->
                    <div id="pan_join_class">
                        <h3>Join Existing Class:</h3>
                        <div class="panel-body well">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="classname" class="col-sm-2 control-label">Tutor Join Code:</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="tutorjoincode" id="tutorjoincode" class="form-control input-lg">
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
                    <!-- ACTIVE INTERFACE -->
                    <div id="pan_manage_class">
                        <h3>Class: <span id="class_name_disp"></span>, Room: <span id="class_room_disp"></span></h3>
                        <div class="panel-body well" id="pan_join_class">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <h2 class="col-sm-6">Student Join Code: </h2>
                                    <span class="col-sm-6"><code id="student_code_disp"></code></span>
                                </div>
                                <!--List of students-->
                                <h3>Next Student</h3>
                                <div class="col-sm-12" id="curr_student">
                                    <div class="col-xs-6 col-sm-3">Name:</div>
                                    <div class="col-xs-6 col-sm-3">Joe</div>
                                    <div class="col-xs-6 col-sm-3">Desk:</div>
                                    <div class="col-xs-6 col-sm-3">12</div>
                                    <div class="col-xs-6 col-sm-3">Waiting Time:</div>
                                    <div class="col-xs-6 col-sm-3">5 minutes</div>
                                    <div class="col-xs-6 col-sm-3">Reason:</div>
                                    <div class="col-xs-6 col-sm-3">Marking</div>
                                    <div id="curr_student_btns" class="col-sm-12">
                                        <button id='dismiss' class='btn-lg btn-primary'>Dismiss</button>
                                        <button id='dismiss' class='btn-lg btn-warning'>Delay</button>
                                        <button id='dismiss' class='btn-lg btn-default'>Undo</button>
                                    </div>
                                </div>
                                <h3>Waiting Students</h3>
                                <table class="table table-striped table-responsive">
                                    <thead> 
                                        <tr>
                                            <th>Name</th>
                                            <th>Desk</th>
                                            <th>Waiting Time</th>
                                            <th>Reason</th>
                                            <th>Dismiss request</th>
                                        </tr>
                                    </thead>
                                    <tbody id="student_table">
                                    <!--template-->
                                        <tr id='template_student'>
                                            <td id='student_name'>John</td>
                                            <td id='student_desk'>Doe</td>
                                            <td id='student_wait_time'>Doe</td>
                                            <td id='reason'>john@example.com</td>
                                            <td><button id='dismiss' class='btn btn-danger'>Dismiss</button></td>
                                        </tr>
                                    </tbody>
                                    <!--end template-->
                                </table>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

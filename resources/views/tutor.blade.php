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
                                    <label for="newclassname" class="col-sm-2 control-label">Name:</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="classname" id="newclassname" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="newclassroom" class="col-sm-2 control-label">Room:</label>
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
                                    <label for="tutorjoincode" class="col-sm-2 control-label">Tutor Join Code:</label>
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
                        <div class="panel-body well">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <h2 class="col-sm-6">Student Join Code: </h2>
                                    <span class="col-sm-6"><code id="student_code_disp"></code></span>
                                </div>
                                <!--Class Controlls-->
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" href="#collapse1">
                                                    <div class="btn btn-warning">Class Settings</div></a>
                                                <div class="btn btn-danger" id="btn_leave_class">Leave Class</div>
                                            </h4>
                                    </div>
                                    <div id="collapse1" class="panel-collapse collapse">
                                        <ul class="list-group">
                                            <li class="list-group-item">
                                                Tutor Join Code: 
                                                <span id="tutorjoincode_settings"></span>
                                                <btn class="btn btn-warning" id="btn_show_tutor_code">Show</btn>
                                                <btn class="btn btn-primary" id="btn_hide_tutor_code">Hide</btn>

                                            </li>
                                            <li class="list-group-item">
                                                Delete Class(Irreversible): 
                                                <btn class="btn btn-danger" id="btn_delete_class">DELETE</btn>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!--List of students-->
                                <h3>Next Student:</h3>
                                <div class="col-sm-12 student_cont" id="curr_student">
                                    <div class="col-xs-6 col-sm-3">Name:</div>
                                    <div class="col-xs-6 col-sm-3" id="curr_name"></div>
                                    <div class="col-xs-6 col-sm-3">Desk:</div>
                                    <div class="col-xs-6 col-sm-3" id="curr_desk"></div>
                                    <div class="col-xs-6 col-sm-3">Waiting Time:</div>
                                    <div class="col-xs-6 col-sm-3 counting" id="curr_time"></div>
                                    <div class="col-xs-6 col-sm-3">Reason:</div>
                                    <div class="col-xs-6 col-sm-3" id="curr_reason" ></div>
                                    <div id="curr_student_btns" class="col-sm-12">
                                        <button id='dismiss_curr' class='btn btn-primary dismiss_top'>Dismiss</button>
                                        <!--<button id='delay_curr' class='btn btn-warning'>Delay</button>
                                        <button id='undo_last' class='btn btn-default'>Undo</button>-->
                                    </div>
                                </div>
                                <h3>Waiting Students:</h3>
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
                                        <tr id='template_student' class="student_list_item student_cont" style="display: none;">
                                            <td class='student_name'>John</td>
                                            <td class='student_desk'>Doe</td>
                                            <td class='student_wait_time counting'>Doe</td>
                                            <td class='reason'>Marks</td>
                                            <td><button class='btn btn-danger dismiss'>Dismiss</button></td>
                                        </tr>
                                    <!--end template-->
                                    </tbody>
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

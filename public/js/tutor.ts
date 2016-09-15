/// <reference path="jquery.d.ts"/>
$(document).ready(function () {
    
    //setup Cross-site-request-forgery token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //hide unwanted stuff
    $('#pan_manage_class').hide();
    $('#tutorjoincode_settings').hide();
    $('#btn_hide_tutor_code').hide();

    //register event handlers
    $("#btn_make_class").one('click', make_class_func);

    $("#btn_join_class").one('click', join_func);
    $("#btn_leave_class").one('click', leave_class);
    $("#btn_delete_class").click(delete_func);

    $("#btn_hide_tutor_code").click(function () {
        $('#tutorjoincode_settings').hide();
        $('#btn_hide_tutor_code').hide();
        $('#btn_show_tutor_code').show();
    })

    $("#btn_show_tutor_code").click(function () {
        $('#tutorjoincode_settings').show();
        $('#btn_hide_tutor_code').show();
        $('#btn_show_tutor_code').hide();
    })

    $(".dismiss_top").click(dismiss_handler);

    //setup number counters
    setInterval(counting, 1000);

});



var failed_attempts: number = 0;
var max_failed_attemps: number = 5;
var main_loop_time: number = 4000;
var loop_timer: number = null;

interface StudentSession {
    name: string;
    desk: string;
    reason: string;
    requested: number;
    needs_help: boolean;
    sid: string;
    delay: number;
}

interface ClassData {
    tutor_code: string;
    student_code: string;
    name: string;
    room: string;
    students: Array<StudentSession>;
    curr_time: number;
}

var class_data: ClassData = null;

function make_class_func() {
    make_class((<HTMLInputElement>$("#newclassname")[0]).value, (<HTMLInputElement>$("#newclassroom")[0]).value,
        function (data) {
            //on success
            $("#btn_make_class").one('click', make_class_func);
            start_class(data.tutor_code, data.student_code, data.class_name, data.class_room);

        },
        function (jqXHR, status, errorThrown) {
            //on error
            $("#btn_make_class").one('click', make_class_func);
            alert("Request Error: " + status + " " + errorThrown);
        });
    $(this).attr('enabled', 'enabled');
}


function join_func() {
    join_class((<HTMLInputElement>$("#tutorjoincode")[0]).value,
        function (data) {
            //on success
            $("#btn_join_class").one('click', join_func);
            start_class(data.tutor_code, data.student_code, data.class_name, data.class_room);
        },
        function (jqXHR, status, errorThrown) {
            //on error
            $("#btn_join_class").one('click', join_func);
            alert("Request Error: " + status + " " + errorThrown);
        });
};

function delete_func() {
    clearInterval(loop_timer);
    $("#btn_delete_class").attr("disabled", "disabled");
    delete_class((<HTMLInputElement>$("#tutorjoincode")[0]).value, leave_class,
        function (jqXHR, status, errorThrown) {
            //on error
            //loop_timer = setInterval(class_loop, main_loop_time);
            if (failed_attempts <= max_failed_attemps) {
                delete_func();
            } else {
                $("#btn_delete_class").removeAttr("disabled");
            }
            failed_fetch();
        })
}

function counting() {
    var counters = $(".counting");
    var n_counters: number = counters.length;
    for (var i: number = 0; i < n_counters; i++) {
        var n: number = Number(counters[i].innerHTML);
        counters[i].innerHTML = (n + 1).toString();

    }
}

function dismiss_handler(event) {
    console.log("dismissing");
    //get element clicked
    var clicked = $(event.target);
    //disable to prevent repeated presses
    clicked.attr("disabled", "disabled");
    //get associated student id
    var element = clicked.closest(".student_cont");
    var sid: string = element.attr("sid");
    //pause main loop during request
    clearInterval(loop_timer);

    update_student_session(false, "", sid, function (data) {
        //success
        loop_timer = setInterval(class_loop, main_loop_time);
        if (data.success == true) {
            //if in the table of students, remove the entry
            if (element.is('tr')) {
                clicked.remove();
            }
            class_loop();
        }
        clicked.removeAttr("disabled");
    }, function (jqXHR, status, errorThrown) {
        //on error
        loop_timer = setInterval(class_loop, main_loop_time);
        alert("Request Error: " + status + " " + errorThrown);
        clicked.removeAttr("disabled");
    });
}

function update_student_session(needs_help, reason, sid, callback, error) {
    console.log("Updating session");
    $.ajax({
        url: "/class/student",
        type: "PUT",
        data: {
            'student_session_id': sid,
            'needs_help': needs_help ? 1 : 0,
            'reason': reason,
        },
        dataType: 'json',
        success: callback,
        error: error,
    });
}

function make_class(name, room, callback, error) {
    console.log("Making new class");
    $.ajax({
        url: "/class/new",
        type: "POST",
        data: {
            'name': name,
            'room': room,
        },
        dataType: 'json',
        success: callback,
        error: error,
    });
}

function join_class(code, callback, error) {
    console.log("Joining class");
    $.ajax({
        url: "/class/tutor",
        type: "GET",
        data: {
            'tutor_code': code,
        },
        dataType: 'json',
        success: callback,
        error: error,
    });
}

function leave_class() {
    clearInterval(loop_timer);
    $("#btn_delete_class").removeAttr("disabled");
    $('#class_name_disp')[0].innerHTML = '';
    $('#class_room_disp')[0].innerHTML = '';
    $('#student_code_disp')[0].innerHTML = '';
    $('#tutorjoincode_settings')[0].innerHTML = '';
    $('#pan_join_class').show();
    $('#pan_make_class').show();
    $('#pan_manage_class').hide();
    $("#btn_leave_class").one('click', leave_class);
}

function delete_class(code, callback, error) {
    console.log("Deleting class");
    $.ajax({
        url: "/class/tutor",
        type: "POST",
        data: {
            'tutor_code': code,
            '_method': 'DELETE',
        },
        dataType: 'json',
        success: callback,
        error: error,
    });
}
function refresh_class(code, callback, error) {
    console.log("Refreshing class");
    $.ajax({
        url: "/class/tutor/refresh",
        type: "GET",
        data: {
            'tutor_code': code,
        },
        dataType: 'json',
        success: callback,
        error: error,
    });
}

function start_class(tutor_code, student_code, name, room) {
    class_data = {
        tutor_code: tutor_code,
        student_code: student_code,
        name: name,
        room: room,
        students: null,
        curr_time: null,
    };
    //change display
    (<HTMLInputElement>$('#tutorjoincode')[0]).value = tutor_code;
    $('#class_name_disp')[0].innerHTML = name;
    $('#class_room_disp')[0].innerHTML = room;
    $('#student_code_disp')[0].innerHTML = student_code;
    $('#tutorjoincode_settings')[0].innerHTML = tutor_code;
    $('#pan_join_class').hide();
    $('#pan_make_class').hide();
    $('#pan_manage_class').show();
    //start loop
    class_loop();
    loop_timer = setInterval(class_loop, main_loop_time);
}

function failed_fetch() {
    failed_attempts = Math.max(failed_attempts, 0);
    failed_attempts++;
    if (failed_attempts > 5) {
        alert("Server not responding, diconnecting session. If the class is still open, try reconnecting. If this issue persists, contact support.");
        leave_class();
        failed_attempts = 0;
    }
}

function class_loop() {
    //update data
    class_data.tutor_code = class_data.tutor_code;
    refresh_class(class_data.tutor_code, function (data) {
        //success
        if (data.success == false) {
            failed_fetch();
            return;
        }
        class_data = {
            tutor_code: data.tutor_code,
            student_code: data.student_code,
            name: data.class_name,
            room: data.class_room,
            students: data.sessions,
            curr_time: data.now,
        }

        console.log(class_data.students);
        <Array<StudentSession>>(class_data.students).sort(function (a, b) {
            return (a.requested - a.delay) - (b.requested - b.delay);
        });


        //process data
        $("[id^=student_id]").remove();
        var student_n: number = class_data.students.length;
        $('#curr_student').hide();
        if (student_n != 0) {
            $('#curr_student').show();
            $("#curr_name")[0].innerHTML = class_data.students[0].name;
            $("#curr_desk")[0].innerHTML = class_data.students[0].desk;
            $("#curr_reason")[0].innerHTML = class_data.students[0].reason;
            $("#curr_time")[0].innerHTML = (class_data.curr_time - class_data.students[0].requested).toString();
            $("#curr_student").attr('sid', class_data.students[0].sid);
        }
        for (var i: number = 1; i < student_n; i++) {
            var student = class_data.students[i];
            var element = $("#template_student").clone().appendTo("#student_table")
                .attr("id", "student_id_" + student.sid).show()
                .attr("sid", student.sid);
            element.find(".student_name")[0].innerHTML = student.name;
            element.find(".student_desk")[0].innerHTML = student.desk;
            element.find(".reason")[0].innerHTML = student.reason;
            element.find(".student_wait_time")[0].innerHTML = (class_data.curr_time - student.requested).toString();

        }
        //re-register handlers
        $(".dismiss").click(dismiss_handler);
    }, function (jqXHR, status, errorThrown) {
        //on error
        failed_fetch();
    });
}
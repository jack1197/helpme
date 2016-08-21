$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#pan_manage_class').hide();
    $('#tutorjoincode_settings').hide();
    $('#btn_hide_tutor_code').hide();

    make_func = function () {
        make_class($("#newclassname")[0].value, $("#newclassroom")[0].value,
            function (data) {
                //on success
                $("#btn_make_class").one('click', make_func);
                start_class(data.tutor_code, data.student_code, data.class_name, data.class_room);

            },
            function (jqXHR, status, errorThrown) {
                //on error
                $("#btn_make_class").one('click', make_func);
                alert("Request Error: " + status + " " + errorThrown);
            });
        $(this).attr('enabled', 'enabled');
    }

    $("#btn_make_class").one('click', make_func);

    join_func = function () {
        join_class($("#tutorjoincode")[0].value,
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

    $("#btn_join_class").one('click', join_func);
    $("#btn_leave_class").one('click', leave_class);
    $("#btn_delete_class").click(function () {
        clearInterval(loop_timer);
        delete_class($("#tutorjoincode")[0].value, leave_class, function (jqXHR, status, errorThrown) {
            //on error
            alert("Request Error: " + status + " " + errorThrown);
            loop_timer = setInterval(class_loop, 4000);
        })
    });

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
    setInterval(counting, 1000);

    $(".dismiss").click(dismiss_handler);

});

function counting() {
    counters = $(".counting");
    n_counters = counters.length;
    for (i = 0; i < n_counters; i++) {
        n = Number(counters[i].innerHTML);
        counters[i].innerHTML = n + 1;

    }
}

function dismiss_handler(event) {
    clicked = $(event.target);
    console.log("dismissing");
    clicked.attr("disabled", "disabled");
    element = clicked.closest(".student_cont");
    sid = element.attr("sid");
    console.log(sid);
    update_student_session(false, "", sid, function (data) {
        if (data.success == true) {
            if (element.is('tr')) {
                clicked.remove();
            }
        }
        clicked.removeAttr("disabled");
    }, function (jqXHR, status, errorThrown) {
        //on error
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

function update_class() {

}

class_data = {
    tutor_code: null,
    student_code: null,
    name: null,
    room: null,
}
loop_timer = null;


function start_class(tutor_code, student_code, name, room) {
    class_data = {
        tutor_code: tutor_code,
        student_code: student_code,
        name: name,
        room: room,
    }
    //change display
    $('#tutorjoincode')[0].value = tutor_code;
    $('#class_name_disp')[0].innerHTML = name;
    $('#class_room_disp')[0].innerHTML = room;
    $('#student_code_disp')[0].innerHTML = student_code;
    $('#tutorjoincode_settings')[0].innerHTML = tutor_code;
    $('#pan_join_class').hide();
    $('#pan_make_class').hide();
    $('#pan_manage_class').show();
    //start loop
    class_loop();
    loop_timer = setInterval(class_loop, 4000);
}

function class_loop() {
    //update data
    tutor_code = class_data.tutor_code;
    refresh_class(tutor_code, function (data) {
        //success
        class_data = {
            tutor_code: data.tutor_code,
            student_code: data.student_code,
            name: data.class_name,
            room: data.class_room,
            students: data.sessions,
            curr_time: data.now,
        }

        console.log(class_data.students);
        class_data.students.sort(function (a, b) {
            return (a.requested - a.delay) - (b.requested - b.delay);
        });
        console.log(class_data.students);
        //process data\
        $("[id^=student_id]").remove();
        student_n = class_data.students.length;
        $('#curr_student').hide();
        if (student_n != 0) {
            $('#curr_student').show();
            $("#curr_name")[0].innerHTML = class_data.students[0].name;
            $("#curr_desk")[0].innerHTML = class_data.students[0].desk;
            $("#curr_reason")[0].innerHTML = class_data.students[0].reason;
            $("#curr_time")[0].innerHTML = class_data.curr_time - class_data.students[0].requested;
            $("#curr_student").attr('sid', class_data.students[0].sid);
        }
        for (i = 1; i < student_n; i++) {
            student = class_data.students[i];
            element = $("#template_student").clone().appendTo("#student_table")
                .attr("id", "student_id_" + student.sid).show()
                .attr("sid", student.sid);
            element.find(".student_name")[0].innerHTML = student.name;
            element.find(".student_desk")[0].innerHTML = student.desk;
            element.find(".reason")[0].innerHTML = student.reason;
            element.find(".student_wait_time")[0].innerHTML = class_data.curr_time - student.requested;

        }
        //re-register handlers
        $(".dismiss").click(dismiss_handler);
    }, function (jqXHR, status, errorThrown) {
        //on error
        alert("Request Error: " + status + " " + errorThrown);
    });
}
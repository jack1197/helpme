/// <reference path="jquery.d.ts"/>

$(document).ready(function () {
    //setup Cross-site-request-forgery token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //hide initially unwanted elements
    $('#pan_active_class').hide();
    $('#btn_scrub_that').hide();

    //setup event handlers
    $("#btn_join_class").one("click", join_func);

    $("#btn_help_me").click(help_me);
    $("#btn_scrub_that").click(scrub_that);

    $("#btn_leave_class").one('click', leave_func);
});

interface StudentSession {
    id: number;
    desk: string;
    name: string;
    needs_help: boolean;
}

interface ClassData {
    ssession: StudentSession;
    class_name: string;
}

var class_data: ClassData;
var loop_timer = null;
var failed_attempts = 0;
var max_failed_attemps = 5;
var main_loop_time = 4000;


function join_func() {
    join_class((<HTMLInputElement>$("#studentjoincode")[0]).value, 
                        (<HTMLInputElement>$("#studentname")[0]).value,
                        (<HTMLInputElement>$("#studentdesk")[0]).value,
        function (data) {
            //on success
            $("#btn_join_class").one("click", join_func); //re-register handler
            if (data.success == false) {
                //bad class code
                alert("Class not found");
            }
            else {
                //setup class
                class_data = data;
                start_class();
            }

        },
        function (jqXHR, status, errorThrown) {
            //on error
            $("#btn_join_class").one("click", join_func);
            alert("Request Error: " + status + " " + errorThrown);

        });
}

function leave_func() {
    clearInterval(loop_timer);
    leave_class(function (data) {
        //on success
        $("#btn_leave_class").one('click', leave_func);
        if (data.success == true) {
            $("#pan_active_class").hide();
            $("#pan_join_class").show();
            clearInterval(loop_timer);
        }
    },
        function (jqXHR, status, errorThrown) {
            //on error
            $("#btn_leave_class").one('click', leave_func);
            alert("Request Error: " + status + " " + errorThrown);
            loop_timer = setInterval(main_loop_time);
        });
}

function join_class(code, name, desk, callback, error) {
    console.log("Joining class");
    $.ajax({
        url: "/class/student",
        type: "GET",
        data: {
            'student_code': code,
            'name': name,
            'desk': desk,
        },
        dataType: 'json',
        success: callback,
        error: error,
    });
}

function leave_class(callback, error) {
    console.log("Leaving class");
    $.ajax({
        url: "/class/student",
        type: "POST",
        data: {
            'student_session_id': class_data.ssession.id,
            '_method': "DELETE",
        },
        dataType: 'json',
        success: callback,
        error: error,
    });

}

function update_session(needs_help, reason, callback, error) {
    console.log("Updating session");
    $.ajax({
        url: "/class/student",
        type: "PUT",
        data: {
            'student_session_id': class_data.ssession.id,
            'needs_help': needs_help ? 1 : 0,
            'reason': reason,
        },
        dataType: 'json',
        success: callback,
        error: error,
    });
}
function refresh_session(callback, error) {
    console.log("Refreshing session");
    $.ajax({
        url: "/class/student/refresh",
        type: "GET",
        data: {
            'student_session_id': class_data.ssession.id,
        },
        dataType: 'json',
        success: callback,
        error: error,
    });
}

function help_me() {
    clearInterval(loop_timer);
    $('#btn_help_me').attr('disabled', 'disabled');
    update_session(true, (<HTMLInputElement>$("#helpreason")[0]).value, function () {
        $('#btn_scrub_that').show();
        $('#btn_help_me').hide();
        $('#btn_help_me').removeAttr('disabled');
        (<HTMLInputElement>$("#helpreason")[0]).disabled = true;
        loop_timer = setInterval(class_loop, main_loop_time);
    },
        function (jqXHR, status, errorThrown) {
            //on error
            loop_timer = setInterval(class_loop, main_loop_time);
            if (failed_attempts <= max_failed_attemps) {
                help_me();
            } else {
                $('#btn_help_me').removeAttr('disabled');
            }
            failed_fetch();

        });
}

function scrub_that() {
    clearInterval(loop_timer);
    $('#btn_scrub_that').attr('disabled', 'disabled');
    update_session(false, '', function () {
        $('#btn_scrub_that').hide();
        $('#btn_scrub_that').removeAttr('disabled');
        $('#btn_help_me').show();
        (<HTMLInputElement>$("#helpreason")[0]).disabled = false;
        loop_timer = setInterval(class_loop, main_loop_time);
    },
        function (jqXHR, status, errorThrown) {
            //on error
            loop_timer = setInterval(class_loop, main_loop_time);
            if (failed_attempts <= max_failed_attemps) {
                scrub_that();
            }
            else {
                $('#btn_scrub_that').removeAttr('disabled');
            }
            failed_fetch();

        });
}

function failed_fetch() {
    failed_attempts = Math.max(failed_attempts, 0);
    failed_attempts++;
    if (failed_attempts > 5) {
        alert("Server not responding, diconnecting session. If the class is still open, try reconnecting. If this issue persists, contact support.");
        $("#pan_active_class").hide();
        $("#pan_join_class").show();
        clearInterval(loop_timer);
        failed_attempts = 0;
    }
}

function start_class() {
    $("#pan_active_class").show();
    $("#pan_join_class").hide();
    $("#curr_class")[0].innerHTML = class_data.class_name;
    $("#curr_desk")[0].innerHTML = class_data.ssession.desk;
    $("#curr_name")[0].innerHTML = class_data.ssession.name;

    //start loop
    class_loop();
    loop_timer = setInterval(class_loop, main_loop_time);
}

function class_loop() {
    refresh_session(function (data) {
        //on success
        if (data.success == false) {
            $("#pan_active_class").hide();
            $("#pan_join_class").show();
            clearInterval(loop_timer);
        }
        else {
            class_data = data;
            $("#curr_class")[0].innerHTML = class_data.class_name;
            $("#curr_desk")[0].innerHTML = class_data.ssession.desk;
            $("#curr_name")[0].innerHTML = class_data.ssession.name;
            if (class_data.ssession.needs_help == true) {
                $('#btn_scrub_that').show();
                $('#btn_help_me').hide();
                (<HTMLInputElement>$("#helpreason")[0]).disabled = true;
            } else {
                $('#btn_scrub_that').hide();
                $('#btn_help_me').show();
                (<HTMLInputElement>$("#helpreason")[0]).disabled = false;
            }
        }
    }, function (jqXHR, status, errorThrown) {
        //on error
        failed_fetch();

    })
}
$(document).ready(function(){
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $('#pan_active_class').hide();
    $('#btn_scrub_that').hide();
    
    $("#btn_join_class").click(function(){
        join_class( $("#studentjoincode")[0].value, $("#studentname")[0].value, $("#studentdesk")[0].value,

        function(data){
            //on success
            class_data = data;
            start_class();

        }, 
        function(jqXHR, status, errorThrown){
            //on error
            alert("Request Error: " + status + " " + errorThrown);

        });
    });

    $("#btn_help_me").click(help_me);
    $("#btn_scrub_that").click(scrub_that);

    $("#btn_leave_class").click(function(){
        leave_class(function(data){
            //on success
            if (data.success == true)
            {
                $("#pan_active_class").hide();
                $("#pan_join_class").show();
                clearInterval(loop_timer);
            }

        }, 
        function(jqXHR, status, errorThrown){
            //on error
            alert("Request Error: " + status + " " + errorThrown);

        });
    });
});

class_data = {};
loop_timer = null;

function join_class(code, name, desk, callback, error){
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

function leave_class(callback, error){
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

function update_session(needs_help, reason, callback, error)
{
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
function refresh_session(callback, error)
{
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

function help_me()
{
    update_session(true, $("#helpreason")[0].value, function(){
        $('#btn_scrub_that').show();
        $('#btn_help_me').hide();
        $("#helpreason")[0].disabled = true;
    }, 
    function(jqXHR, status, errorThrown){
        //on error
        alert("Request Error: " + status + " " + errorThrown);

    });
}

function scrub_that()
{
    update_session(false, '',function(){
        $('#btn_scrub_that').hide();
        $('#btn_help_me').show();
        $("#helpreason")[0].disabled = false;
    }, 
    function(jqXHR, status, errorThrown){
        //on error
        alert("Request Error: " + status + " " + errorThrown);

    });
}

function start_class(){
    $("#pan_active_class").show();
    $("#pan_join_class").hide();
    $("#curr_class")[0].innerHTML = class_data.class_name;
    $("#curr_desk")[0].innerHTML = class_data.ssession.desk;
    $("#curr_name")[0].innerHTML = class_data.ssession.name;

    //start loop
    class_loop();
    loop_timer = setInterval(class_loop, 4000);
}

function class_loop()
{
    refresh_session(function(data){
        //on success
        if (data == []){

        }
        class_data = data;
        $("#curr_class")[0].innerHTML = class_data.class_name;
        $("#curr_desk")[0].innerHTML = class_data.ssession.desk;
        $("#curr_name")[0].innerHTML = class_data.ssession.name;
        if (class_data.ssession.needs_help){
            $('#btn_scrub_that').show();
            $('#btn_help_me').hide();
            $("#helpreason")[0].disabled = true;
        }else{
            $('#btn_scrub_that').hide();
            $('#btn_help_me').show();
            $("#helpreason")[0].disabled = false;
        }
    }, function(jqXHR, status, errorThrown){
        //on error
        alert("Request Error: " + status + " " + errorThrown);
    })
}
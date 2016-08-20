$(document).ready(function(){
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });

    $("#btn_make_class").click(function(){
        make_class($("#newclassname")[0].value, $("#newclassroom")[0].value, 
        function(data){
            //on success
            start_class(data.tutor_code, data.student_code, data.class_name, data.class_room);

        }, 
        function(jqXHR, status, errorThrown){
            //on error
            alert("Request Error: " + status + " " + errorThrown);

        });
    });
    
    $("#btn_join_class").click(function(){
        join_class($("#tutorjoincode")[0].value, 
        function(data){
            //on success
            start_class(data.tutor_code, data.student_code, data.class_name, data.class_room);

        }, 
        function(jqXHR, status, errorThrown){
            //on error
            alert("Request Error: " + status + " " + errorThrown);

        });
    });
});



function make_class(name, room, callback, error){
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

function join_class(code, callback, error){
    console.log("makeclass run");
    $.ajax({
        url: "/class/join",
        type: "GET",
        data: {
            'tutor_code': code,
        },
        dataType: 'json',
        success: callback,
        error: error,
    });
}

class_data = {
    tutor_code: null,
    student_code: null,
    name: null,
    room: null,
}
loop_timer = null;

function start_class(tutor_code, student_code, name, room){
    class_data = {
        tutor_code: tutor_code,
        student_code: student_code,
        name: name,
        room: room,
    }
    //change display
    $('#class_name_disp')[0].innerHTML = name;
    $('#class_room_disp')[0].innerHTML = room;
    $('#student_code_disp')[0].innerHTML = student_code;
    $('#pan_join_class')[0].style = 'display: none;';
    $('#pan_make_class')[0].style = 'display: none;';
    $('#pan_manage_class')[0].style = '';
    //start loop
    class_loop();
    loop_time = setTimeout(class_loop, 4000);
}

function class_loop()
{
    tutor_code = class_data.tutor_code;

}
<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Requests;
use Auth;
use App\ClassSession;
use Illuminate\Http\Request;

class ClassSessionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function newclass(Request $request)
    {
        $this->validate($request, []);

        //generating codes between 1000 0000 and 9999 9999 for aesthetics
        $student_code =  mt_rand(10000000,99999999);    
        while(ClassSession::where('student_code', $student_code)->count() > 0)
        {
            $student_code = mt_rand(10000000,99999999);
        }   

        $tutor_code =  mt_rand(10000000,99999999);    
        while(ClassSession::where('tutor_code', $tutor_code)->count() > 0)
        {
            $student_code = mt_rand(10000000,99999999);      
        }

        $csession = new ClassSession;
        $csession->student_code = $student_code;
        $csession->tutor_code = $tutor_code;
        $csession->class_name = $request->name;
        $csession->class_room = $request->room;
        $csession->last_accessed = Carbon::now();

        if(Auth::check())
        {
            $csession->tutor_creator_id = Auth::user()->id;
        }


        $csession->save();

        return response()->json([
            'student_code' => $student_code,
            'tutor_code' => $tutor_code, 
            'class_name' => $request->name,
            'class_room' => $request->room
            ]);
    }

    public function joinclass(Request $request)
    {
        $this->validate($request, []);

        $csession = ClassSession::where('tutor_code', $request->tutor_code)->firstOrFail();
        $csession->last_accessed = Carbon::now();
        $csession->save();
        
        return response()->json([
            'student_code' => $csession->student_code,
            'tutor_code' => $csession->tutor_code, 
            'class_name' => $csession->class_name,
            'class_room' => $csession->class_room,
            ]);

    }

}

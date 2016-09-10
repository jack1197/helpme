<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Requests;
use Auth;
use App\ClassSession;
use App\StudentSession;
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

    public function tutorjoinclass(Request $request)
    {
        $this->validate($request, [
            'tutor_code' => 'min:10000000|max:99999999|integer|required',
            ]);

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

    public function tutordeleteclass(Request $request)
    {
        $this->validate($request, [
            'tutor_code' => 'min:10000000|max:99999999|integer|required',
        ]);

        $csession = ClassSession::where('tutor_code', $request->tutor_code)->firstOrFail();
        $csession->delete();
        
        return response()->json(['success' => true]);

    }

    public function tutorrefreshclass(Request $request)
    {
        $this->validate($request, [
            'tutor_code' => 'min:10000000|max:99999999|integer|required',
        ]);

        $csessions = ClassSession::where('tutor_code', $request->tutor_code);
        if ($csessions->count() != 1) {
            return response()->json([
                'success' => false,
            ]);
        }
        $csession = $csessions->firstOrFail();
        $ssessions = StudentSession::where('class_id', $csession->id)
            ->where("needs_help", true)->get();

        $student_sessions = array();

        foreach($ssessions as $ssession)
        {
            $student_session = [
                'sid' => $ssession->id,
                'name' => $ssession->name,
                'desk' => $ssession->desk,
                'needs_help' =>$ssession->needs_help,
                'delay' =>$ssession->delay_time,
                'reason' =>$ssession->help_reason,
                'requested' => Carbon::parse($ssession->asked_for_help)->timestamp,
            ];
            array_push($student_sessions, $student_session);
        }

        return response()->json([
            'success' => true,
            'now' => Carbon::now()->timestamp,
            'student_code' => $csession->student_code,
            'tutor_code' => $csession->tutor_code, 
            'class_name' => $csession->class_name,
            'class_room' => $csession->class_room,
            'sessions' => $student_sessions,
            ]);


    }

    //


    public function studentjoinclass(Request $request)
    {
        $this->validate($request, [
            'student_code' => 'min:10000000|max:99999999|integer|required',
            'name' => 'max:100|string|required',
            'desk' => 'max:100|string',
            ]);


        $csessions = ClassSession::where('student_code', $request->student_code);
        if ( $csessions->count() != 1)
        {
            return response()->json(['success' => false]);
        }
        $csession = $csessions->firstOrFail();


        $ssession = new StudentSession;
        $ssession->name = $request->name;
        $ssession->desk = $request->desk;
        $ssession->class_id = $csession->id;
        $ssession->student_id = Auth::check() ? Auth::user()->id : null;
        $ssession->last_accessed = Carbon::now();
        $ssession->needs_help = false;
        $ssession->save();

        
        return response()->json([
            'class_name' => $csession->class_name,
            'class_room' => $csession->class_room,
            'ssession' => $ssession,
            ]);

    }

    public function studentleaveclass(Request $request)
    {
        $ssession = StudentSession::where('id', $request->student_session_id)->firstOrFail();
        $ssession->delete();
        
        return response()->json(['success' => true]);
    }
    public function studentrefreshclass(Request $request)
    {
        $ssessions = StudentSession::where('id', $request->student_session_id);
        if ( $ssessions->count() != 1)
        {
            return response()->json(['success' => false]);
        }
        $ssession = $ssessions->firstOrFail();
        $csession = $ssession->classsession;
        
        return response()->json([
            'success' => true,
            'class_name' => $csession->class_name,
            'class_room' => $csession->class_room,
            'ssession' => $ssession,
            ]);
    }

    public function studentupdateclass(Request $request)
    {
        $this->validate($request, [
            'needs_help' => 'boolean|required',
            'student_session_id' => 'integer|required',
        ]);
        
        $ssession = StudentSession::where('id', $request->student_session_id)->firstOrFail();
        if ($request->needs_help && ! $ssession->needs_help )
        {
            $ssession->needs_help = true;
            $ssession->asked_for_help = Carbon::now();
            $ssession->help_reason = $request->reason;
        } 
        elseif (!$request->needs_help)
        {
            $ssession->needs_help = false;
        }
        $ssession->save();
        
        return response()->json(['success' => true]);
    }


}

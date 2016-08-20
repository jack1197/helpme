<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Auth;
use App\ClassSession;
use Illuminate\Http\Request;

class TutorController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('tutor');
    }

    public function getupdate(Request $request)
    {
        
        if(ClassSession::where('tutor_code', $request->tutor_code)->count() != 1)
        {
            return reponse()->json([]);
        }
        
        $csession = ClassSession::where('tutor_code', $request->tutor_code)->firstOrFail();

    }

}

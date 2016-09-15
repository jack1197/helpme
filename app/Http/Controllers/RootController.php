<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class RootController extends Controller
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
    public function index()
    {

        start_measure('render', 'page render');
        $a = view('welcome');
        stop_measure('render', 'page render');
        return $a;
    }

    public function redirect_index()
    {
        return redirect('/');
    }
}

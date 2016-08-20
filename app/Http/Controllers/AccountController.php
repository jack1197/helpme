<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('web');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!isset($errors))
        {
            $errors = null;
        }
        return view('account', [
            'user' => Auth::user(),
            ]);
    }

    public function update(Request $request)
    {
        if ($request->submit_type == "cancel")
        {
            return view('account', [
                'user' => Auth::user()
                ]);
        }

        $user = Auth::user();
        if (! Auth::attempt(['email' => $user->email, 'password' => $request->password]))
        {
            return view('account', [
            'user' => Auth::user(),
            'error' => 'Invalid Password',
            ]);
        }

        if($request->submit_type == "update")
        {
            $checkemailunique = true; 
            if ($user->email == $request->email)
            {
                $checkemailunique = false;
            }

            $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255'.
                ($checkemailunique ? '|unique:users' : ''),
            ]);

            $user->email = $request->email;
            $user->name = $request->name;
            $user->update();
            Auth::logout();
            Auth::attempt(['email' => $user-> email, 'password' => $request->password]);

            return view('account', [
            'user' => Auth::user(),
            'success' => 'User Information Updated!',
            ]);
        }
        elseif($request->submit_type == "changepw")
        {
            $this->validate($request, [
                'newpassword' => 'required|min:8|max:64|confirmed'
            ]);

            $user->password = bcrypt($request->newpassword);
            $user->update();

            return view('account', [
            'user' => Auth::user(),
            'success' => 'Password Updated!',
            ]);
        }
    }

}

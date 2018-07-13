<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function showLogin()
    {
        return view('Login');
    }

    public function login(Request $req)
    {

        if ($this->attemptLogin($req)) {
            if (Auth::user()->role_id) {
                $this->redirectTo = '/admin/type';

                return $this->sendLoginResponse($req);
            }

            $this->redirectTo = session('oldAccessUrl') ? session('oldAccessUrl') : '/';

            return $this->sendLoginResponse($req);
        }
        return redirect('/login')->withInput()->with('failed', ' ');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        return redirect('/');
    }

    public function register()
    {
        return view('Reg');
    }

    public function doRegister(Request $req)
    {
        $rules = [
            'password' => 'required|between:6,50',
            'email' => 'email',
            'password_confirmation' => 'required|same:password'
        ];
        $messages = [
            'required' => __('required'),
            'same' => __('same'),
            'email' => __('email'),
            'between' => __('between'),
        ];
        $validation = $req->validate($rules, $messages);
        $detectAccount = User::where('email', $req->email)->first();

        if ($detectAccount) {
            return back()->with('wrong', ' ');
        }
        $req->merge([
            'password'=>bcrypt($req->password_confirmation),
        ]);
        $affectedRows = User::create($req->all());
        
        if ($affectedRows) {
            return redirect('/login')->with('success',' ');
        }        
    }
}

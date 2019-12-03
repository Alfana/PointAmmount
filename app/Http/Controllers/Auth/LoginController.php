<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $this->generateApiToken($user);
    }
    
    protected function generateApiToken($user)
    {
        $token = Str::random(60);
        $user->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();
    }

    public function loginWithApi(Request $request){
        $validator = Validator::make($request->all(), [
          'email' => 'required',
          'password' => 'required|string',
        ]);
    
        if ($validator->fails()) {
          return $this->failed($validator->errors(),'Email atau password anda tidak sesuai');
        }
    
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
          $user = Auth::user();
          $this->generateApiToken($user);
       
          return $this->success($user);
        }
        else{
          return $this->failed([],'Mohon maaf akun tidak ditemukan.');
        }
    }
}

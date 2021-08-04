<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

use App\Models\User;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function username()
    {
      return 'username';
    }

    public function verify(Request $request){
      $credential = [
          'email' => $request->email,
          'password' => $request->password,
      ];

      $remember_me  = ( !empty( $request->remember_me ) )? TRUE : FALSE;

      if(Auth::attempt($credential)){
          $user = User::where(["email" => $credential['email']])->first();
          
          Auth::login($user, $remember_me);

          return redirect(route('dashboard'));
      }
  }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}

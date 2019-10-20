<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = '/home';


    public function field(Request $request)
    {
        $login = $request->login;
        $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'login';
        request()->merge([$fieldType => $login]);
        return $fieldType;
    }
    public function logout(Request $request)
    {
        Auth::user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
    public function login(Request $request)
    {
        $field = $this->field($request);
        $request->validate([
            $field => 'required|string|exists:users',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request([$field, 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized',
                'errors' => [
                    'password' => ['Password not match']
                ]
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'user' => $user,
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth:api')->except('login');
    }
}

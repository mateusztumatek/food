<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerificationEmail;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    protected $redirect = 'http://localhost:8080';

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['Verification email was send on '. $request->user()->email]);
    }
    public function verify(Request $request, $id, $hash)
    {
        $user = User::find($id);
        if(!$user) return redirect($this->redirect)->withErrors(['User not found']);
        if($user->verification_code == $hash){
            $user->email_verified_at = Carbon::now();
            $user->update();
            return redirect($this->redirect);
        }else{
            return redirect($this->redirect)->withErrors(['Bad hash']);
        }

       /* if (! hash_equals((string) $request->route('id'), (string) $request->user()->getKey())) {
            throw new AuthorizationException;
        }

        if (! hash_equals((string) $request->route('hash'), sha1($request->user()->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'This user is already verified']);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }*/
/*        return redirect(config('app.front_url').'/login');*/

    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only('resend');
/*        $this->middleware('signed')->only('verify');*/
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}

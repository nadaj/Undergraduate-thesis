<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use SendGrid;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\User;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    private $redirectTo = '/login';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct(Guard $auth, PasswordBroker $broker)
    {
        $this->auth = $auth;
        $this->broker = $broker;
        $this->subject = 'e-Glasanje: Promena lozinke';
        $this->middleware('guest');
    }

    public function postEmail(Request $request)
    {
        return $this->sendResetLinkEmail($request);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        // provera da li postoji korisnik sa unetim emailom
        $user = User::where('email', '=', $request['email'])
                    ->where('active', '=', 1)
                    ->where('confirmed', '=', 1)
                    ->get();

        if ($user->isEmpty())
            return redirect()->back()->with([
            'fail' => 'Ne postoji korisnik u sistemu sa unetim e-mailom!'
        ]);

        $sendgrid = new SendGrid('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');
        $email = new SendGrid\Email();
        $token = Str::random(60);
        $url = url('/password/reset/' . $token);
        $m = "<p><a href='".$url."'>Kliknite ovde</a> 
            kako biste promenili lozinku.</p>";
   
        $email
            ->addTo($request['email'])
            ->setFrom('votingsystemetf@gmail.com')
            ->setSubject('e-Glasanje: Promena lozinke')
            ->setHtml($m)
        ;

        try {
            $sendgrid->send($email);
            DB::table('password_resets')->insert([
                'email' => $request['email'],
                'token' => $token,
                'created_at' =>  Carbon::now()
            ]);
        } catch(\SendGrid\Exception $e) {
            echo $e->getCode();
            foreach($e->getErrors() as $er) {
                echo $er;
            }
        }

        return redirect()->back()->with([
            'success' => 'Primićete poruku o promeni lozinke na Vašoj e-mail adresi.'
        ]);
    }

    public function postReset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6'
        ]);

        // provera da li postoji korisnik sa unetim emailom
        $user = User::where('email', '=', $request['email'])->get();

        if ($user->isEmpty())
            return redirect()->back()->with([
            'fail' => 'Ne postoji korisnik u sistemu sa unetim e-mailom!'
        ]);

        if (! $request['token'])
            return redirect()->route('error')->with([
            'fail' => 'Nije validan zahtev za promenu lozinke!'
        ]);

        // provera tokena
        $passResets = DB::table('password_resets')->whereRaw("email='".$request['email']."' and token='".
            $request['token']."'")->get();

        if (empty($passResets))
            return redirect()->back()->with([
            'fail' => 'Nije validan zahtev za promenu lozinke!'
        ]);

        DB::table('password_resets')->where('email', '=', $request['email'])->delete();

        User::where('email', '=', $request['email'])->update([
            'password' => bcrypt($request['password'])
        ]);

        return redirect()->route('login');
    }

}

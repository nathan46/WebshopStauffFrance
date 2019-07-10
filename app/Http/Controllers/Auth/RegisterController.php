<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Notifications\WelcomeUser;

use Illuminate\Http\Request;
use Mail;
use Notification;
use Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/register';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('superAdmin');
    }

    /**
     * Get a validator for an incoming registration request.jJnCCeRE
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:STAUFF_Users'],
            'password' => ['required', 'string', 'min:8'],
            'CardCode' => ['required', 'alpha_num', 'min:7', 'max:7', 'regex:/[A-Z][0-9]{6}/', 'unique:STAUFF_Users'],
            'telephone_fixe' => ['required', 'digits:10'],
            'service' => ['required', 'string', 'max:255'],
            'fonction' => ['required', 'string', 'max:255'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if ($data['telephone_portable'] !== null) {
            if (trim($data['telephone_portable']) !== '') {
                $portable = $data['telephone_portable'];
            } else {
                $portable = null;
            }
        } else {
            $portable = null;
        }

        $u = User::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'email' => $data['email'],
            'telephone_fixe' => $data['telephone_fixe'],
            'telephone_portable' => $portable,
            'service' => $data['service'],
            'fonction' => $data['fonction'],
            'password' => bcrypt($data['password']),
            'CardCode' => $data['CardCode'],
            'admin' => 1
        ]);

        $user = new User();
        $user->email = $data['email'];
        $user->notify(new WelcomeUser($data));

        return $u;
    }

    /*public function sendEmail(Request $request){
        $title = "Identifiants pour Stauff.com";
        $content = 'content';
        $user_email = "informatique.stagiaire@stauffsa.com";
        $user_name = "Nathan";


        try
        {
            $data = ['email'=> $user_email,'name'=> $user_name,'subject' => $title, 'content' => $content];
            Mail::send('emails/'.$content, $data, function($message) use($data)
            {
                $subject=$data['subject'];
                $message->from('stauff.sa@gmail.com');
                $message->to($data['email'])->subject($subject);
            });
        }
        catch (\Exception $e)
        {
            dd($e->getMessage());
        }
    }*/
}

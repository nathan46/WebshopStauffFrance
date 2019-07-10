<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Mail\NewUserWelcome;

use Mail;
use Notification;

trait RegistersUsers
{
    use RedirectsUsers;

    /**
    * Show the application registration form.
    *
    * @return \Illuminate\Http\Response
    */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
    * Handle a registration request for the application.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        //$this->guard()->login($user);

        //$this->sendEmail($request);

        /*return $this->registered($request, $user)
        ?: redirect($this->redirectPath());*/

        return redirect($this->redirectPath())->with('status', 'Le compte a bien été créé !');
    }
    //old
    /*protected function sendEmail(Request $request){
        $title = "Identifiants pour Stauff.com";
        $content = 'content';
        $user_email = $request->input('email');
        $user_name = "Nathan";
        $mdp = $request->input('password');


        try
        {
            $data = ['email'=> $user_email,'name'=> $user_name,'subject' => $title, 'content' => $content, 'password' => $mdp];
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


    /**
    * Get the guard to be used during registration.
    *
    * @return \Illuminate\Contracts\Auth\StatefulGuard
    */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
    * The user has been registered.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  mixed  $user
    * @return mixed
    */
    protected function registered(Request $request, $user)
    {
        //
    }
}

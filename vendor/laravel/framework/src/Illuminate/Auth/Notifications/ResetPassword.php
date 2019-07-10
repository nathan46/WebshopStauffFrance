<?php

namespace Illuminate\Auth\Notifications;

use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    /**
    * The password reset token.
    *
    * @var string
    */
    public $token;

    /**
    * The callback that should be used to build the mail message.
    *
    * @var \Closure|null
    */
    public static $toMailCallback;

    /**
    * Create a notification instance.
    *
    * @param  string  $token
    * @return void
    */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
    * Get the notification's channels.
    *
    * @param  mixed  $notifiable
    * @return array|string
    */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
    * Build the mail representation of the notification.
    *
    * @param  mixed  $notifiable
    * @return \Illuminate\Notifications\Messages\MailMessage
    */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return (new MailMessage)
        ->greeting('Bonjour,')
        ->subject(Lang::getFromJson('Réinitialisation de mot de passe'))
        ->line(Lang::getFromJson('Nous vous envoyons cet email puisque nous avons recu une demande de réinitialisation de votre mot de passe.'))
        ->action(Lang::getFromJson('Réinitialiser le mot de passe'), url(config('app.url').route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false)))
        ->line(Lang::getFromJson('Ce lien de Réinitialisation de mot de passe expire dans :count minutes', ['count' => config('auth.passwords.users.expire')]))
        ->line(Lang::getFromJson("Si vous n'avez pas fait cette demande, aucune action n'est requises"));
    }

    /**
    * Set a callback that should be used when building the notification mail message.
    *
    * @param  \Closure  $callback
    * @return void
    */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}

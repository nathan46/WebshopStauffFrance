@component('mail::message')

# Votre compte vient d'être créé !

Vous pouvez vous identifier avec :

Votre adresse Email : **{{$email}}**
<br/>
Votre mot de passe : **{{$password}}**

@component('mail::button', ['url' => $actionUrl])
{{ $actionText }}
@endcomponent

Cordialement,<br>
{{ config('app.name') }}

@isset($actionText)
@slot('subcopy')
@lang(
    "Si le bouton \":actionText\" ne fonctionne pas, copiez-collez le lien ci-dessous\n".
    'dans votre navigateur web : [:actionURL](:actionURL)',
    [
        'actionText' => $actionText,
        'actionURL' => $actionUrl,
    ]
)
@endslot
@endisset
@endcomponent

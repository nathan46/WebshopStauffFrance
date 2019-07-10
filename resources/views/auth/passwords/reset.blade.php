@extends('layouts.beforeAuth')

@section('content')
<form name="resetpw" method="POST" action="{{ route('password.update') }}" onsubmit="showWait('button_reset','sanduhr_reset');">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    <table>
        <tr>
            <td><b>Adresse Email:</b></td>
            <td>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email }}" required autocomplete="email" size="40" readonly style="background-color:#e5e5e5">
            </td>
        </tr>

        <tr>
            <td><b>Nouveau mot de passe:</b></td>
            <td>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="" autocomplete="new-password" required size="40" autofocus>
            </td>
        </tr>

        <tr>
            <td><b>Confirmer le mot de passe:</b></td>
            <td>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" value="" autocomplete="new-password" required size="40">
            </td>
        </tr>

        <td>
            <input id="button_reset" type="submit" class="buttongreen" value="Changer le mot de passe">
            <div id="sanduhr_reset" style="display:none;"><img id="sanduhr_reset_img" src="{{ asset('pictures/preloader.gif')}}" height="20" border="0"></div>
        </td>

    </table>

    @error('password')
    <br/><br/>
    <span style="color:red">
        <strong>{{ $message }}</strong>
    </span>
    @enderror

    @error('email')
    <br/><br/>
    <span style="color:red">
        <strong>{{ $message }}</strong>
    </span>
    @enderror

    @if (session('status'))
    <br/><br/>
    <span style="color:blue" >
        <strong>{{ session('status') }}</strong>
    </span>
    @endif

</form>
@endsection

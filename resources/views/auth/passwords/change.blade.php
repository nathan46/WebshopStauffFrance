@extends('layouts.beforeAuth')

@section('content')
<form name="changepw" action="{{ route('password.new') }}" method="POST">
    @csrf
    <table>
        <tr height="20"><td colspan="2"></td></tr>

        <tr>
            <td><b>Mot de passe actuel</b>:</td>
            <td><input id="oldpassword" type="password" name="oldpassword" size="40" class="form-control @error('oldpassword') is-invalid @enderror" required autofocus></td>
        </tr>

        <tr>
            <td><b>Nouveau mot de passe</b>:</td>
            <td><input id="password" type="password" name="password" size="40" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" required></td>
        </tr>

        <tr>
            <td><b>Confirmer le mot de passe</b>:</td>
            <td><input id="password-confirm" type="password" name="password_confirmation" size="40" class="form-control" autocomplete="new-password" required></td>
        </tr>

        <tr>
            <td colspan="2">
                <input id="button_change" type="submit" class="buttongreen" value="Changer le mot de passe">
            </td>
        </tr>

    </table>

    @error('oldpassword')
    <br/><br/>
    <span style="color:red">
        <strong>{{ $message }}</strong>
    </span>
    @enderror

    @error('password')
    <br/><br/>
    <span style="color:red">
        <strong>{{ $message }}</strong>
    </span>
    @enderror

</form>

@if(session('msgError'))
<br/><br/>
<span style="color:red">
    <strong>{{ session('msgError') }}</strong>
</span>
@endif

@if(session('msgSuccess'))
<br/><br/>
<span style="color:blue">
    <strong>{{ session('msgSuccess') }}</strong>
</span>
<form name="acceuil" action="{{ route('home') }}" method="GET">
    <input id="button_acceuil" type="submit" class="buttongreen" value="Aller à la page d'acceuil =>" style="float:right">
</form>
@endif

@endsection

@extends('layouts.beforeAuth')

@section('content')

<form name="mailpw" method="POST" action="{{ route('password.email') }}" onsubmit="showWait('button_mail','sanduhr_mail');">
    @csrf
    <table>
        <tr>
            <td><b>Adresse Email:</b></td>
            <td>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus size="40">
            </td>
            <td>
                <input id="button_mail" type="submit" class="buttongreen" value="Envoyer le lien de récupération par email">
                <div id="sanduhr_mail" style="display:none;">
                    <img id="sanduhr_mail_img" src="{{ asset('pictures/preloader.gif')}}" height="20" border="0">
                </div>
            </td>
        </tr>
    </table>

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

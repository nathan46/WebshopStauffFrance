@extends('layouts.beforeAuth')

@section('content')
<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <form name="login" action="{{ route('login') }}" method="POST" onsubmit="showWait('button_login','sanduhr_login');">
                @csrf
                <table>
                    <tr>
                        <td colspan="2">
                            <b>Bienvenue sur le Webshop de STAUFF France</b><br/><br/>
                            <br/><br/>
                            <div style="color: #007857;">Identifiants</div>
                            <br/>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Adresse Email:</b></td>
                        <td><input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus size="40"></td>

                    </tr>
                    <tr>
                        <td><b>Mot de passe:</b></td>
                        <td><input id="password" type="password" name="password" required size="40"></td>
                    </tr>
                    <tr><td height="10" colspan="2"></td></tr>
                    <tr>
                        <td></td>
                        <td>
                            <div id="button_login" style="display:block;">

                                <input id="" type="submit" class="buttongreen" value="Se connecter">

                            </div>
                            <div id="sanduhr_login" style="display:none;"><img id="sanduhr_login_img" src="{{ asset('pictures/preloader.gif')}}" height="20" border="0"></div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        @if (Route::has('password.request'))
                        <td>
                            <a href="{{ route('password.request') }}">
                                Mot de passe oublié?<br/>Cliquez ici pour en demander un nouveau!
                            </a>
                        </td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">
                            @error('email')
                            <br/><br/>
                            <span class="invalid-feedback" style="color:red" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            @error('password')
                            <br/><br/>
                            <span class="invalid-feedback" style="color:red" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror


                        </td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>
<div style="position: absolute; top: 20px; right: 20px">

    <img border="0" src="{{ asset('pictures/Produktnews_EN.jpg')}} ">
</div>
<div style="position: absolute; bottom: 0px;">
    <img border="0" src="{{ asset('pictures/homebanner_en.gif')}}">
</div>
@endsection

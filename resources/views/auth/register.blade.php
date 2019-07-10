@extends('layouts.afterAuth')

@section('titre','Créer un compte Administrateur')

@section('monCompte')
<td id='monCompte' class="item active" style="border-right: solid 2px #007857;; border-left: solid 1px #007857;; border-top: solid 1px #007857;">
@endsection

@section('content')
<form name="register" method="POST" action="{{ route('register') }}" onsubmit="showWait('button_register','sanduhr_register');">
    @csrf

    <table>
        <tr>
            <td><b><span style="color: red">*</span>Nom:</b></td>
            <td>
                <input id="nom" type="text" class="form-control @error('nom') is-invalid @enderror" name="nom" value="{{ old('nom') }}" required size="40" autofocus>
            </td>
        </tr>

        <tr>
            <td><b><span style="color: red">*</span>Prenom:</b></td>
            <td>
                <input id="prenom" type="text" class="form-control @error('prenom') is-invalid @enderror" name="prenom" value="{{ old('prenom') }}" required size="40">
            </td>
        </tr>

        <tr>
            <td><b><span style="color: red">*</span>Adresse Email:</b></td>
            <td>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required size="40">
            </td>
        </tr>

        <tr>
            <td><b><span style="color: red">*</span>Téléphone fixe:</b></td>
            <td>
                <input id="telephone_fixe" type="text" class="form-control @error('telephone_fixe') is-invalid @enderror" name="telephone_fixe" value="{{ old('telephone_fixe') }}" required size="40">
            </td>
        </tr>

        <tr>
            <td><b>Téléphone portable:</b></td>
            <td>
                <input id="telephone_portable" type="text" class="form-control @error('telephone_portable') is-invalid @enderror" name="telephone_portable" value="{{ old('telephone_portable') }}" size="40">
            </td>
        </tr>

        <tr>
            <td><b><span style="color: red">*</span>Fonction:</b></td>
            <td>
                <input id="fonction" type="text" class="form-control @error('fonction') is-invalid @enderror" name="fonction" value="{{ old('fonction') }}" required size="40">
            </td>
        </tr>

        <tr>
            <td><b><span style="color: red">*</span>Service:</b></td>
            <td>
                <input id="service" type="text" class="form-control @error('service') is-invalid @enderror" name="service" value="{{ old('service') }}" required size="40">
            </td>
        </tr>

        <tr>
            <td><b>Mot de passe:</b></td>
            <td>
                <?php
                use Illuminate\Support\Facades\Hash;

$hashed_random_password = str_random(8);
                ?>
                <input id="password" type="text" name="password" value="{{$hashed_random_password}}" required size="40" readonly style="background-color:#e5e5e5">
            </td>
        </tr>

        <tr>
            <td><b><span style="color: red">*</span>CardCode:</b></td>
            <td>
                <input id="CardCode" type="text" class="form-control" name="CardCode" class="form-control @error('CardCode') is-invalid @enderror" value="{{ old('CardCode') }}" required size="40">
            </td>
        </tr>
        <tr>
            <td></td>
            <td>

                <input id="button_register" type="submit" class="buttongreen" value="Créer le compte">
                <div id="sanduhr_register" style="display:none;"><img id="sanduhr_register_img" src="{{ asset('pictures/preloader.gif')}}" height="20" border="0"></div>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <br/>
                <span style="color: red">* Champs obligatoire</span>
            </td>
        </tr>
    </table>

    @error('telephone_fixe')
        <br/><br/>
        <span style="color:red">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

    @error('telephone_portable')
        <br/><br/>
        <span style="color:red">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

    @error('fonction')
        <br/><br/>
        <span style="color:red">
            <strong>{{ $message }}</strong>
        </span>
    @enderror

    @error('service')
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

    @error('CardCode')
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

@extends('layouts.afterAuth')

@section('titre','Créer un compte utilisateur')

@section('content')
<form name="addUser" method="POST" action="{{route('nouvelutilisateur.ajouter')}}" onsubmit="showWait('button_add','sanduhr_add');">
    @csrf

    <input type="hidden" value="{{$fin}}" name="finEmail"/>
    <?php
        use Illuminate\Support\Facades\Hash;

$hashed_random_password = str_random(8);
    ?>
    <input id="password" type="hidden" name="password" value="{{$hashed_random_password}}"/>

    <table>
        <tr>
            <td style="font-size: 15px;">Nom<span style="color: red">*</span></td>
            <td>
                <input id="nom" type="text" class="form-control @error('nom') is-invalid @enderror" name="nom" value="{{ old('nom') }}" required size="35" autofocus style="font-size: 15px">
            </td>
        </tr>

        <tr>
            <td style="font-size: 15px;">Prénom<span style="color: red">*</span></td>
            <td>
                <input id="prenom" type="text" class="form-control @error('prenom') is-invalid @enderror" name="prenom" value="{{ old('prenom') }}" required size="35"style="font-size: 15px">
            </td>
        </tr>

        <tr>
            <td style="font-size: 15px;">Adresse Email<span style="color: red">*</span></td>
            <td>
                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required size="20" style="font-size: 15px"> <span style="font-size: 15px;" >{{$fin}} </span>

            </td>
        </tr>

        <tr>
            <td style="font-size: 15px;">Téléphone fixe<span style="color: red">*</span></td>
            <td>
                <input id="telephone_fixe" type="text" class="form-control @error('telephone_fixe') is-invalid @enderror" name="telephone_fixe" value="{{ old('telephone_fixe') }}" required size="35" style="font-size: 15px">
            </td>
        </tr>

        <tr>
            <td style="font-size: 15px;">Téléphone portable</td>
            <td>
                <input id="telephone_portable" type="text" class="form-control @error('telephone_portable') is-invalid @enderror" name="telephone_portable" value="{{ old('telephone_portable') }}" size="35" style="font-size: 15px">
            </td>
        </tr>

        <tr>
            <td style="font-size: 15px;">Fonction<span style="color: red">*</span></td>
            <td>
                <input id="fonction" type="text" class="form-control @error('fonction') is-invalid @enderror" name="fonction" value="{{ old('fonction') }}" required size="35" style="font-size: 15px">
            </td>
        </tr>

        <tr>
            <td style="font-size: 15px;">Service<span style="color: red">*</span></td>
            <td>
                <input id="service" type="text" class="form-control @error('service') is-invalid @enderror" name="service" value="{{ old('service') }}" required size="35" style="font-size: 15px">
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <br/>
                <span style="color: red">* Champ obligatoire</span>
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

    <hr align='left' width='76%'>

    <table border="0" width="80%">
        <thead>
            <tr>
                <td colspan="2" style="font-size: 17px">Autorisations :</td>
            </tr>
            <tr height="15px"></tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-size: 15px">Possibilité de passer une commande/accéder au panier </td>
                <td>
                    <label class="switch" align="right">
                        <input class="switch-input" type="checkbox" name="panier"/>
                        <span class="switch-label" data-on="oui" data-off="non"></span>
                        <span class="switch-handle"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td style="font-size: 15px">Possibilité d'effectuer des demandes via la liste</td>
                <td>
                    <label class="switch" align="right">
                        <input class="switch-input" type="checkbox" name="demande"/>
                        <span class="switch-label" data-on="oui" data-off="non"></span>
                        <span class="switch-handle"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td style="font-size: 15px">Accès aux conditions de vente</td>
                <td>
                    <label class="switch" align="right">
                        <input class="switch-input" type="checkbox" name="condition"/>
                        <span class="switch-label" data-on="oui" data-off="non"></span>
                        <span class="switch-handle"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td style="font-size: 15px">Accès au suivi de commande</td>
                <td>
                    <label class="switch" align="right">
                        <input class="switch-input" type="checkbox" name="suivi"/>
                        <span class="switch-label" data-on="oui" data-off="non"></span>
                        <span class="switch-handle"></span>
                    </label>
                </td>
            </tr>
            <tr>
                <td style="font-size: 15px">Accès aux prix</td>
                <td>
                    <label class="switch" align="right">
                        <input class="switch-input" type="checkbox" name="prix"/>
                        <span class="switch-label" data-on="oui" data-off="non"></span>
                        <span class="switch-handle"></span>
                    </label>
                </td>
            </tr>

        </tbody>
    </table>
    <div align="right">
        <input id="button_add" type="submit" class="buttongreen" value="Créer le compte">
        <div id="sanduhr_add" style="display:none;"><img id="sanduhr_add_img" src="{{ asset('pictures/preloader.gif')}}" height="20" border="0"></div>
    </div>


</form>
@endsection

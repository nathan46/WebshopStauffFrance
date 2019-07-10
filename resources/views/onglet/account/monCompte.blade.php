@extends('layouts.afterAuth')

@section('titre','Mon compte')

@section('monCompte')
<td id='monCompte' class="item active" style="border-right: solid 2px #007857;; border-left: solid 1px #007857;; border-top: solid 1px #007857;">
@endsection

@section('content')
@if(auth()->user()->superAdmin == 1)
    <table width="100%">
        <tr>
            <td align='right' width="120" style="padding: 0px 6px 6px 0px">
                <a href="{{route('register')}}" onfocus="this.blur();">
                    <img src="{{asset('pictures/Button-add-account.png')}}" width="120" border="0"/>
                </a>
            </td>

            <td>
                <a href="{{route('register')}}" style="text-decoration:none">
                    <p class="bodytext"><strong>Créer un compte client</strong></p>
                </a>
            </td>
        </tr>
    </table>

@else
    @foreach($client as $c)
        {{$c->nom}}<br>
        {{$c->adresse}}<br>
        {{$c->County}}<br/>
        {{$c->ZipCode}}&nbsp;{{$c->ville}}<br>
        {{$c->pays}}
    @endforeach

    <br><br>

    <table width="100%">
        <tr>
            <td align='right' width="120" style="padding: 0px 6px 6px 0px">
                <a @if(auth()->user()->acces_suivi == 1) href="{{route('commande.afficheselect')}}" @else onclick="return createAlert('Vous n\'êtes pas autorisé à voir le suivi de commande')" href="#" @endif>
                    <img src="{{asset('pictures/Button-Bestellinformationen-gruen.jpg')}}" width="120" border="0"/>
                </a>
            </td>

            <td>
                <a @if(auth()->user()->acces_suivi == 1) href="{{route('commande.afficheselect')}}" @else onclick="return createAlert('Vous n\'êtes pas autorisé à voir le suivi de commande')" href="#" @endif style="text-decoration:none">
                    <p class="bodytext"><strong>Suivi de commandes</strong></p>
                </a>
            </td>
            @if(auth()->user()->admin == 1)
                <td align='right' width="120" >
                    <a href="{{route('nouvelutilisateur.affiche')}}" >
                        <img src="{{asset('pictures/Button-add-account.png')}}" width="120"  border="0"/>
                    </a>
                </td>

                <td>
                    <a href="{{route('nouvelutilisateur.affiche')}}" style="text-decoration:none">
                        <p class="bodytext"><strong>Créer un compte utilisateur</strong></p>
                    </a>
                </td>
            @endif

        </tr>

        <tr>
            <td align='right' width="120" style="padding: 0px 6px 6px 0px">
                <a @if(auth()->user()->acces_condition == 1) href="{{route('conditionlivraisonpaiement')}}" @else onclick="return createAlert('Vous n\'êtes pas autorisé à voir les conditions')" href="#" @endif>
                    <img src="{{asset('pictures/Button-Liefer-und-Zahlungsbedingungen-gruen.jpg')}}" width="120"  border="0"/>
                </a>
            </td>

            <td>
                <a @if(auth()->user()->acces_condition == 1) href="{{route('conditionlivraisonpaiement')}}" @else onclick="return createAlert('Vous n\'êtes pas autorisé à voir les conditions')" href="#" @endif style="text-decoration:none">
                    <p class="bodytext"><strong>Conditions de livraison / Paiement</strong></p>
                </a>
            </td>
            @if(auth()->user()->admin == 1)
                <td align='right' width="120">
                    <a href="{{route('modifprofil.affiche')}}" onfocus="this.blur();">
                        <img src="{{asset('pictures/Button-update-account.png')}}" width="120"  border="0"/>
                    </a>
                </td>

                <td>
                    <a href="{{route('modifprofil.affiche')}}" style="text-decoration:none">
                        <p class="bodytext"><strong>Modifier le profil d'un utilisateur</strong></p>
                    </a>
                </td>
            @endif
        </tr>

    </table>

@endif
@endsection

@extends('layouts.afterAuth')

@section('titre','Conditions de livraison / Paiement')

@section('content')
    <table cellpadding="0" cellspacing="0" border="0" width="100%">

        <tr><td colspan="5" height="20"></td></tr>
        <tr>
            <td colspan="5">
                <table cellpadding="2" cellspacing="2" border="0">
                    <tr>
                        <td><b>Conditions de paiement :</b></td>
                        <td></td>
                        <td>{{$client->PymntGroup}}</td>
                    </tr>
                    <tr>
                        <td><b>Mode de paiement :</b></td>
                        <td></td>
                        <td>{{$client->Descript}}</td>
                    </tr>

                    @if($client->U_GROUPAGE)
                        <tr>
                            <td><b>Jour de groupage :</b></td>
                            <td></td>
                            <td>{{$client->U_GROUPAGE}}</td>
                        </tr>
                    @endif

                    @if($client->U_FRANCO)
                        <tr>
                            <td><b>Montant Franco :</b></td>
                            <td></td>
                            <td>{{$client->U_FRANCO}}&nbsp;€</td>
                        </tr>
                    @endif

                    @if($client->U_MINI)
                        <tr>
                            <td><b>Minimun de commande :</b></td>
                            <td></td>
                            <td>{{$client->U_MINI}}&nbsp;€</td>
                        </tr>
                    @endif

                </table>
            </td>
        </tr>

    </table>

@endsection

@extends('layouts.afterAuth')

@section('titre','Importer une commande à votre panier')

@section('importCommand')
<td id='importCommand' class="item active" style="border-right: solid 1px #007857;; border-left: solid 1px #007857;; border-top: solid 1px #007857;">
    @endsection

    @section('content')
    <style>
        p{
            font-size: 12px;
        }

        b{
            font-size: 14.5px;
        }
    </style>
    <table cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td>
                <p>
                    <b>Importer une commande</b>
                </p>
            </td>
        </tr>

        <tr>
            <td>
                <p>Vous pouvez facilement importer votre commande (Code STAUFF & quantité) dans votre panier en utilisant un fichier .CSV (délimité par des points virgules).<br/> Veuillez renseigner au minimum les colonnes code et quantité nommées réspectivement <b>code</b> et <b>quantite</b>. </p>
                <p>Exemple de fichier :
                    <br/>
                    <img src="{{asset('pictures/UploadExample.png')}}" border="0" title="Le fichier doit contenir au moins le code Stauff ainsi que la quantité.">
                </p>
                <p>Veuillez suivre ces 3 étapes pour importer votre commande.</p>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    <b>1. Sélectionner vos données</b>
                </p>

                <p>Cliquez sur le bouton ci-dessous pour sélectionner votre fichier. </p>
                <form name="f_basketimport" action="{{route('importcommande')}}" method="post" enctype="multipart/form-data" onsubmit="showWait('button_orderimport','sanduhr_orderimport');">
                    @csrf
                    <table cellpadding="5" cellspacing="0" border="0">
                        <tr>
                            <td colspan="2">
                                <input type="file" name="FileUpload[]" size="60" multiple />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" height="10"></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" name="clearfirst" checked="checked"/>Cochez cette case pour vider votre panier avant d'importer votre commande.
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <p>
                                    <b>2. Importer les données</b>
                                </p>
                                <p>Cliquez sur le boutton 'Importer' pour importer votre commande avec les données du fichier.</p>
                                <div id="button_orderimport" style="display: block;">
                                    <input id="" type="submit" class="buttongreen" value="Importer">
                                </div>
                                <div id="sanduhr_orderimport" style="display: none;"><img id="sanduhr_orderimport_img" src="{{asset('pictures/preloader.gif')}}" height="20" border="0"></div>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </form>
            </td>
            <td width="40"></td>
            <td>
            </td>
        </tr>

        <tr>
            <td>
                <p><b>3. Vérifier l'importation</b></p>
                <p>Vérifier le contenu de votre panier aprés l'importation</p>
                <table>
                    <tr>
                        <td>
                            <a href="{{route('affichepanier')}}"><img src="{{asset('pictures/Button-Einkaufswagen-grau.jpg')}}" width="60" border="0"></a>
                        </td>
                    </tr>
                </table>
                <p></p>
            </td>
        </tr>
    </table>

    @endsection

@extends('layouts.afterAuth')

@section('titre','Demande de Prix/Délai')

@section('requestList')
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function checkForm(nb){
    showWait('button_sendRequest','sanduhr_sendRequest');
    for (var i = 0; i < nb; i++) {
        var prix = $("#prix"+i).is(":checked");
        var delai = $("#delai"+i).is(":checked");

        if(!(prix || delai)){
            alert('Vous devez cochez au moins une des cases pour la demande numéro '+(i+1));
            stopShowWait('button_sendRequest','sanduhr_sendRequest');
            return false;
        }
    }

    return true;
}

function addLine(url){

    var qty = "1";
    var text = "";

    var dataString ="qty="+qty+"&text="+text;
    //console.log(dataString);
    $.ajax({
        type: "POST",
        url: "ajouterRequestList",
        data: dataString,

        success: function(data){
            var nb = parseInt($('#CountArticlesRequestList').text());
            $('#CountArticlesRequestList').text(nb+1);
            window.location.reload();

        }
    });

}

function saveInput(type, index)
{

    if(type == "prix" || type == "delai"){
        var typeValue = $("#"+type+index).is(":checked");
        console.log(typeValue);
    }else{
        var typeValue = document.getElementById(type+index).value;
    }


    var dataString = type+"="+typeValue+"&index="+index;
    console.log(dataString);
    $.ajax({
        type: "POST",
        url: "saveRequestListInput",
        data: dataString,

        success: function(data){

        }
    });
}

</script>
<td align="center" valign="bottom" style="font-weight:normal;font-size:10px;background-color:#dddddd;">
    @endsection

    @section('content')

    <form name="f_requestlist" action="{{route('envoyerrequestlist')}}" enctype="multipart/form-data" method="post" @isset($requestList) onsubmit="return checkForm({{count($requestList)}})" @endisset>
        @csrf

        <table id="allRequest" cellpadding="3" cellspacing="0" border="0" width="100%">

            <tr bgcolor="#cccccc">
                <th align="right" width="15">No.</th>
                <th style="border-left:2px solid #FFFFFF;" width="65">Type de demande</th>
                <th style="border-left:2px solid #FFFFFF;" width="50">Code Stauff</th>
                <th style="border-left:2px solid #FFFFFF;" width="235">Désigniation</th>
                <th style="border-left:2px solid #FFFFFF;" width="50">Quantité</th>
                <th width="30">&nbsp;</th>
            </tr>

            @isset($requestList)
                @foreach($requestList as $article)
                    <tr height="10">
                        <td colspan="5"></td>
                    </tr>

                    <tr bgcolor="#eeeeee">

                        <td align="right">
                            {{$loop->index + 1}}
                        </td>

                        <td style="border-left:2px solid #FFFFFF;">
                            <input type="checkbox" id="prix{{$loop->index}}" onchange="saveInput('prix','{{$loop->index}}');" @if(auth()->user()->acces_prix == 0) disabled @else @if($article['prix'] === 'true') checked @endif  @endif> <b>Prix</b> </input>
                            <br/>
                            <br/>
                            <br/>
                            <input type="checkbox" id="delai{{$loop->index}}" onchange="saveInput('delai','{{$loop->index}}')" @if(auth()->user()->acces_prix == 0) checked disabled @else @if($article['delai'] === 'true') checked @endif @endif> <b>Delai</b> </input>
                        </td>

                        <td style="border-left:2px solid #FFFFFF;">
                            @isset($article['itemCode'])
                                {{$article['itemCode']}}
                            @endisset
                        </td>

                        <td style="border-left:2px solid #FFFFFF;">
                            @isset($article['itemCode'])
                                <b>{{$article['desc']}}</b><br/><br/>
                            @endisset

                            <textarea style="resize: none;" id="text{{$loop->index}}"  cols="35" rows="5" onblur="saveInput('text','{{$loop->index}}')" placeholder="Veuillez écrire votre demande ici en étant suffisamment précis pour qu'elle puisse être traitée">@isset($article['text']){{$article['text']}}@endisset</textarea>
                            <br/>
                            <input type="file" name="FileUpload{{$loop->index}}[]" multiple />

                        </td>

                        <td style="border-left:2px solid #FFFFFF;">
                            <input type="text" id="qty{{$loop->index}}" value="{{$article['qty']}}" size="4" onblur="saveInput('qty','{{$loop->index}}')"/>
                        </td>

                        <td align="right">
                            <a href="{{route('afficherequestlist')}}?delete={{$loop->index}}" onClick="return confirm('Voulez vous supprimer cet article de cette liste ?');" title="Supprimer cet élément de la liste">
                                <img src="{{asset('pictures/del.jpg')}}" border="0"/>
                            </a>
                        </td>
                    </tr>

                @endforeach
            @endisset
            <tr id="ici" height="10">
                <td colspan="5"></td>
            </tr>

            <tr bgcolor="#eeeeee">
                <td></td>
                <td></td>

                <td >
                    <input id="ajouterLigne" type="button" class="buttongreen" value="+ Ajouter un ligne" onClick="addLine(); return false;">
                </td>

                <td  align="center">
                    <input size="30" type="text" name="numCommande" value="" placeholder="Numéro de la demande" required @if(!isset($requestList)) disabled @endif>
                </td>

                <td align="left">
                    <div id="button_sendRequest" style="display:block;">
                        <input id="envoyerRequestList" type="submit" value="Envoyer les demandes" @if(!isset($requestList)) class="buttongray" disabled @else class="buttongreen" @endif>
                    </div>
                    <div id="sanduhr_sendRequest" style="display:none;">
                        <img id="sanduhr_sendRequest_img" src="{{asset('pictures/preloader.gif')}}" height="20" border="0">
                    </div>
                </td>

                <td align="right">
                    <a href="{{route('afficherequestlist')}}?delete=all" onClick="return confirm('Voulez vous vider la liste des demandes ?');" title="Vider la liste des demandes">
                        <img src="{{asset('pictures/del.jpg')}}" border="0"/>
                    </a>
                </td>
            </tr>
        </table>
    </form>

@endsection

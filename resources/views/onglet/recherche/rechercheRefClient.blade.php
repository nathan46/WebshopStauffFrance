@extends('layouts.afterAuth')

@section('titre', 'Recherche par référence client')

@section('content')

<table cellpadding="0" cellspacing="0" border="0" align="center">
    <tr>
        <td>
            <form name="f_directsearch" action="{{route('rechercheparrefclient')}}" method="get" onsubmit="showWait('button_matnr_descr','sanduhr_matnr_descr');">
                @csrf
                <table cellpadding="5" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <table class="twoCols" style="width:1px;" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td class="col1" style="">
                                        <div id="Help_search_matnr_descr" style="display: none; position:absolute; top:0px; left:0px; max-width: 300px;background-color: #ffffff; filter:Alpha(opacity=100);background-repeat:no-repeat; border:2px; border-color:#007857; border-style: solid;padding:5px;-moz-opacity: 1.0; opacity: 1.0; z-index: 99999;">
                                            Rechercher via un code ou une désignation STAUFF, avec les caractères spéciaux (ex: 106.4/06.4-PA).
                                        </div>
                                        <table border="0">
                                            <tr>
                                                <td>
                                                    <div>
                                                        <img src="{{asset('pictures/Suche-Kunden-Mat-gruen.jpg')}}" border="0">
                                                    </div>
                                                </td>
                                                <td>
                                                    <nobr>
                                                        <b>Référence client</b>
                                                    </nobr>

                                                    <br/><br/>

                                                    Recherche&nbsp;<img src="{{asset('pictures/info-icon.png')}}" border="0" onmouseover="showHelp(event,true,'Help_search_matnr_descr');" onmouseout="showHelp(event,false,'Help_search_matnr_descr');"/>
                                                    <br/>

                                                    <table border="0">
                                                        <tr>
                                                            <td>
                                                                <input type="text" name="search_matnr_descr" value="{{ $input }}" size="20" maxlength="40" onkeypress="return CheckEnter(event,'matnr_descr');" required/>
                                                            </td>

                                                            <td>
                                                                <div id="button_matnr_descr" style="display:block;">
                                                                    <input id="" type="submit" class="buttongreen" value="Rechercher">
                                                                </div>
                                                                <div id="sanduhr_matnr_descr" style="display:none;">
                                                                    <img id="sanduhr_matnr_descr_img" src="{{asset('pictures/preloader.gif')}}" height="20" border="0">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @if($nb > 0)
                    <tr>
                        <td>
                            <hr/>
                        </td>
                    </tr>

                </table>
            </form>
        </td>
    </tr>
</table>
<table align="center">
    <tr>
        <td>
            <script type="text/javascript">
            function showproductdetails (ev,pict,url)
            {
                var box = document.getElementById('productdetails');

                document.getElementById('productdetails_pict').src = encodeURI(pict);
                document.getElementById('productdetails_text').src = url;

                var posx = 0;
                var posy = 0;
                var scrollPos = 0;
                posx = ev.layerX;
                posy = ev.layerY;
                scrollPos = window.pageYOffset; // Firefox

                box.style.left = posx+15 + "px";
                box.style.top = posy-250 + "px";
                if ( posy-250 < scrollPos )
                box.style.top = posy + "px";
                box.style.display='inline-block';
            }
            function resizeNettopreis(iframeid)
            {
                var iFrameObj = document.getElementById(iframeid);
                if(iFrameObj)
                iFrameObj.height = Math.max(iFrameObj.contentWindow.document.body.offsetHeight, 30);
            }
            </script>

            <div id="productdetails" style="display: none; position: absolute; width:550px;background-color: #ffffff; border:2px; border-color:#007857; border-style: solid;-moz-opacity: 1.0; opacity: 1.0; filter:Alpha(opacity=100);z-index: 99999;">
                <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
                    <tr bgcolor="#007857">
                        <td style="color:#ffffff; font-size:11px; vertical-align:middle;" height="25">
                            <b>&nbsp;&nbsp;Détails du produit</b>
                        </td>
                        <td align="right">
                            <a href="#" onclick="javascript:document.getElementById('productdetails').style.display='none'; return false;">
                                <img src="{{asset('pictures/close-icon.png')}}" border="0"/>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td width="200" align="middle">
                            <img id="productdetails_pict" border="0" src="">
                        </td>
                        <td>
                            <iframe src="" id="productdetails_text" width="400" height="0" frameborder="0"></iframe>
                        </td>
                    </tr>
                </table>
            </div>

            <form name="f_addtobasket" action="" method="post"  onSubmit="return(false);">

                <input type="hidden" name="mcount" value="1"/>

                <table cellpadding="2" cellspacing="2" border="0">
                    <tr bgcolor="#cccccc">
                        <th >
                            Stauff Mat. No.
                        </th>

                        <th>
                            Client Mat. No.
                        </th>

                        <th align="middle">
                            Aperçu
                        </th>

                        <th>
                            Désigniation
                        </th>

                        <th>
                            Prix net&nbsp;(EUR)&nbsp;/<br>Date de disponibilité
                        </th>

                        <th></th>

                    </tr>
                    <div id="LightBox1" style="display: none; position:absolute; top:0px; left:0px; bottom:0px; right:0px; width:130px; height:130px;background-color: #cccccc; filter:Alpha(opacity=100);background-repeat:no-repeat; border:1px solid #000000;-moz-opacity: 1.0; opacity: 1.0;">
                    </div>

                    @foreach($articles as $article)
                    <tr bgcolor="#eeeeee">
                        <td>
                            {{$article->itemCode}} &nbsp; FR<br/>
                            {{$article->U_CodesSTD}} &nbsp; DE<br/><br/>


                            <a href="#" onClick="javascript:showproductdetails(event, 'pictures/default-image.jpg','{{route('productdetails')}}?code={{$article->itemCode}}'); return false;">
                                Details
                            </a>
                            <!-- {{route('productdetails').'?code='.$article->itemCode}} -->
                        </td>

                        <td align="middle">
                            <strong>
                                {{$input}}
                            </strong>
                        </td>

                        <td>
                            <img src="{{asset('pictures/default-image.jpg')}}" border="0" height="65" onmouseover="zoomPict(event,true,'pictures/material/Clamp-Assembly-SP-5XX-PP-DP-AS-M-W3.jpg');" onmouseout="zoomPict(event,false);"/>

                        </td>
                        <td nowrap>
                            <b>{{$article->itemName}}</b>
                        </td>

                        <td>
                            <iframe src="prixStock?code={{$article->itemCode}}&premier=1" align="top" width="150" marginheight="0" marginwidth="0" frameborder="0" name="nettopreis1" id="nettopreis-{{$article->itemCode}}" onload="resizeNettopreis('nettopreis-{{$article->itemCode}}')" scrolling="no"></iframe>
                        </td>

                        <td>
                            <a id="panier-{{$article->itemCode}}" style="text-decoration:none" title="Ajouter au panier" href="javascript:{}" >
                                <img src="pictures/Button-Einkaufswagen-grau.jpg" border="0" width="36"/>
                            </a>
                            <br/><br/>
                            Qty. <input type="edit" id="input-{{$article->itemCode}}" name="input-{{$article->itemCode}}" value="1" size="3" maxlength="5" title="Quantity"/>
                            <br/><br/>

                        </td>
                    </tr>

                    <script type="text/javascript">

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $(document).ready(function(){
                        $('a[id="panier-{{$article->itemCode}}"]').click(function(){
                            $.confirm({
                                title: '<br/> Voulez vous ajouter cet article a votre panier ?',
                                content: '',
                                buttons: {
                                    confirm:{
                                        text: 'oui',
                                        btnClass: 'btn-stauff',
                                        keys: ['enter'],
                                        action: function(){
                                            var itemCode = "{{$article->itemCode}}";
                                            var desc = "{{$article->itemName}}";
                                            var qty = $('#input-{{$article->itemCode}}').val();

                                            var dataString = "itemCode="+itemCode+"&desc="+desc+"&qty="+qty;
                                            $.ajax({
                                                type: "POST",
                                                url: "ajouterAuPanier",
                                                data: dataString,

                                                success: function(data){
                                                    var nb = parseInt($('#CountArticles').text());
                                                    $('#CountArticles').text(nb+1);
                                                    //confirm("L'article a bien été ajouté au panier !");
                                                }
                                            });
                                        }
                                    },
                                    cancel:{
                                        text: 'non',
                                    }
                                }
                            });
                        });
                    });

                    </script>
                    
                    @endforeach

                </table>
            </form>

        </td>

    </tr>

</table>
@else
</table>
</form>

</td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="5">
    <tr id="tr_noresult">
        <td>
            <span style="font-size:14px;">
                Malheureusement il n'y a pas de résultat pour votre Recherche.<br/><br/>
                Merci de nous contacter pour plus d'informations
            </span>
        </td>
    </tr>
</table>

@endif
@endsection

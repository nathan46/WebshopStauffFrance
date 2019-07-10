<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
    <meta http-equiv="expires" content="0">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="pragma" content="no-cache">
    <meta name="csrf-token" content="{{ csrf_token()}}" />

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/lukad.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-confirm.css')}}" />

    <script language="JavaScript1.2" type="text/javascript" src="{{ asset('js/page.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-3.4.1.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-confirm.js')}}"></script>

</head>

<body style="margin:0px;padding:0px" bgcolor="#eeeeee">

    <script language="JavaScript">

    function showWait (url,code)
    {
        var box = document.getElementById('Sanduhr');
        box.style.display = "block";

        var ie= navigator.userAgent.indexOf('MSIE') != -1 || navigator.userAgent.indexOf('Trident') != -1;
        if (ie)
        {
            var progressbar = document.getElementById("Sanduhr_img");
            if ( progressbar )
            progressbar.src = progressbar.src;
        }

        var qty = window.parent.document.getElementById("input-"+code);
        if ( qty )
        url = url + '&qty=' + qty.value;
        window.open(url,'_self');
    }
    </script>

    <script type="text/javascript">



    function addRequestList(type){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        @if(auth()->user()->acces_demande == 1)

            window.parent.$.confirm({
                title: '<br/> Voulez vous ajouter cette demande ?',
                content: '',
                draggable: false,
                buttons: {
                    confirm:{
                        text: 'oui',
                        btnClass: 'btn-stauff',
                        keys: ['enter'],

                        action: function(){
                            var itemCode = "{{request('code')}}";
                            var desc = "";
                            var qty = "{{request('qty')}}";
                            var text = "";

                            var dataString = "itemCode="+itemCode+"&desc="+desc+"&qty="+qty+"&text="+text+"&"+type+"=true";
                            //console.log(dataString);
                            $.ajax({
                                type: "POST",
                                url: "ajouterRequestList",
                                data: dataString,

                                success: function(dejaDansLaListe){
                                    //if(dejaDansLaListe == 'non'){
                                        var nb = parseInt(window.parent.document.getElementById('CountArticlesRequestList').childNodes[0].data);
                                        window.parent.document.getElementById('CountArticlesRequestList').childNodes[0].data = nb+1;
                                    //}else{

                                    //}
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

            @else
                window.parent.$.confirm({
                    title: '<br/> Vous n\'êtes pas autorisé à faire des demandes',
                    content: '',
                    draggable: false,
                    buttons: {
                        cancel:{
                            text: 'OK',
                            btnClass: 'btn-stauff',
                            keys: ['enter'],
                        },
                    }
                });
            @endif
    }

    </script>

    <table border="0" cellpadding="0" cellspacing="0">
        <tr id="content" style="display:block">
            <td valign="top" align="left">
                @if(!isset($premier))
                    @if(auth()->user()->acces_prix == 1)
                        @if($prix != false)
                            <b>{{number_format($prix,2,',',' ')}} / PC</b>
                        @else
                            <b>Nous consulter</b>
                            <a style="text-decoration: none" href="javascript:{}" title="Ajouter à vos demandes" onclick="addRequestList('prix');return false;">
                                <img src="{{asset('pictures/Button-Merkzettel-grau.jpg')}}" border="0" height="20">
                            </a>
                        @endif
                    @else
                        <span style="font-size: 20px">****</span>
                    @endif

                    @isset($cond)
                        @if($cond > 1 )
                            <br>
                            Vendu par {{$cond}}
                        @endif
                    @endisset
                    <br><br>
                    <table border="0">
                        <tr>
                            <td align="left">
                                <b>Date</b>
                            </td>
                            <td align="left">
                                <b>Qty.</b>
                            </td>
                        </tr>
                        @foreach($dateDispo['dates'] as $d)
                            <tr>
                                @if($dateDispo['qty'][$loop->index] !== 0)
                                    <td align="left">
                                        @if($d !== false)
                                            {{$d}}
                                        @else
                                            <b>Nous consulter</b>
                                            <a href="javascript:{}" title="Ajouter à vos demandes" onclick="addRequestList('delai');return false;">
                                                <img src="{{asset('pictures/Button-Merkzettel-grau.jpg')}}" border="0" height="20">
                                            </a>
                                        @endif
                                    </td>
                                @endif

                                @if($dateDispo['qty'][$loop->index] !== 0)
                                    <td align="left">
                                        {{$dateDispo['qty'][$loop->index]}}
                                    </td>
                                @endif
                            </tr>
                        @endforeach

                    </table>

                    <br>
                @endif

                <a href="" onClick="showWait('{{route('prixstock')}}?code={{request('code')}}','{{request('code')}}'); return false;">Vérifier prix & disponibilité</a>
            </td>
        </tr>
    </table>
    <div id="Sanduhr" style="display: none; position:absolute; top:0px; left:0px; width:100%; height:100%;background-color: #eeeeee; filter:Alpha(opacity=100);background-repeat:no-repeat; border:0px;-moz-opacity: 1.0; opacity: 1.0; z-index: 99999;">
        <img src="{{asset('pictures/preloader.gif')}}" border="0" width="20" id="Sanduhr_img">
    </div>

</body>
</html>

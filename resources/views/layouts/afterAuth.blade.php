
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
@php
header("Cache-Control: no-store, no-cache, auto-revalidate");
@endphp
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
    <meta http-equiv="expires" content="0">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="pragma" content="no-cache">
    <meta name="csrf-token" content="{{ csrf_token()}}" />

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>STAUFF:&nbsp;Webshop</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/lukad.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/switch_button.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-confirm.css')}}" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('pictures/favicon.ico')}}" />

    <script language="JavaScript1.2" type="text/javascript" src="{{ asset('js/page.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-3.4.1.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-confirm.js')}}"></script>

</head>
<body>

    <div id="root">

        <div id="head">
            <img id="logo" src="{{ asset('pictures/Banner-Stauffwebshop-oben.jpg')}}" alt="Walter Stauffenberg GmbH &amp; Co. KG"/>
            <div id="language">
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <img src="{{ asset('pictures/close-icon.png')}} " border="0"/>
                    Déconnexion
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

            </div>

            <table id="headTable" cellpadding="0" cellspacing="5" border="0" bordercolor="red">

                <tr>
                    <td class="green" colspan="4">
                        Walter Stauffenberg GmbH & Co. KG
                    </td>
                </tr>
                <tr>
                    <td>
                        <div id="naviMain">
                            <table id="naviTable" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    @hasSection('acceuil')
                                    @yield('acceuil')
                                    @else
                                    <td id='acceuil' class="item" style="border-right: solid 1px #007857;; border-left: solid 1px #007857;; border-top: solid 1px #007857;">
                                        @endif
                                        <a href="{{route('home')}}">
                                            <img src="{{asset('pictures/Button-home-grau.jpg')}}" width="85" height="71" border="0" alt="Home"/>
                                        </a>
                                        <div class="naviText">
                                            <a style="font-weight:normal;font-size:10px;" href="{{route('home')}}">
                                                Acceuil
                                            </a>
                                        </div>
                                    </td>


                                    <td width="5"></td>
                                    @hasSection('crossReference')
                                    @yield('crossReference')
                                    @else
                                    <td id='crossReference' class="item" style="border-right: solid 1px #007857;; border-left: solid 1px #007857;; border-top: solid 1px #007857;">
                                        @endif

                                        <a href="">
                                            <img src="{{asset('pictures/Button-Umschluesselung-grau.jpg')}}" width="85" height="71" border="0" alt="Cross Reference" />
                                        </a>
                                        <div class="naviText">
                                            <a style="font-weight:normal;font-size:10px;" href="">
                                                Cross Reference
                                            </a>
                                        </div>
                                    </td>

                                    <td width="5"></td>
                                    @hasSection('importCommand')
                                    @yield('importCommand')
                                    @else
                                    <td id='importCommand' class="item" style="border-right: solid 1px #007857;; border-left: solid 1px #007857;; border-top: solid 1px #007857;">
                                        @endif

                                        <a @if(auth()->user()->acces_panier == 1) href="{{route('importcommande')}}" @else onclick="return createAlert('Vous n\'êtes pas autorisé à importer des commandes')" href="#" @endif>
                                            <img src="{{asset('pictures/Button-Bestellimport-grau.jpg')}}" width="85" height="71" border="0" alt="Order Import" />
                                        </a>
                                        <div class="naviText">
                                            <a style="font-weight:normal;font-size:10px;" href="{{route('importcommandeaffiche')}}">
                                                import commande
                                            </a>
                                        </div>
                                    </td>

                                    <td width="5"></td>
                                    @hasSection('monCompte')
                                    @yield('monCompte')
                                    @else
                                    <td id='monCompte' class="item" style="border-right: solid 2px #007857;; border-left: solid 1px #007857;; border-top: solid 1px #007857;">
                                        @endif

                                        <a href="{{route('moncompte.affiche')}}">
                                            <img src="{{asset('pictures/Button-MyAccount-grau.jpg')}}" width="85" height="71" border="0" alt="My Account" />
                                        </a>
                                        <div class="naviText">
                                            <a style="font-weight:normal;font-size:10px;" href="">
                                                Mon compte
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td id="searchNregion" width="499" align="center">
                        <div id="Help_search_global" style="display: none; position:absolute; top:0px; left:0px; text-align:left;background-color: #ffffff; filter:Alpha(opacity=100);background-repeat:no-repeat; border:2px; border-color:#007857; border-style: solid;padding:5px;-moz-opacity: 1.0; opacity: 1.0; z-index: 99999;">
                            Rechercher via un code ou une désignation STAUFF, avec les caractères spéciaux  <br/> (ex: 106.4/06.4-PA).
                        </div>
                        <div id="searchSite" style="display:block">

                            <form action="{{route('rechercheparcode')}}" method="get" name="searchform" id="searchform" style="margin:0px; padding:0px;" onsubmit="showWait('button_globalsearch','sanduhr_globalsearch');">
                                @csrf
                                <input type="hidden" name="searchtype" value="matnr_descr">
                                <table id="searchboxTable" border="0" cellpadding="0" cellspacing="0">
                                    <tr height="1">
                                        <td align="right">
                                            <img src="{{asset('pictures/info-icon.png')}}" border="0" onmouseover="showHelp(event,true,'Help_search_global');" onmouseout="showHelp(event,false,'Help_search_global');"/>
                                        </td>
                                    </tr>
                                    <tr height="1">
                                        <td>
                                            <input type="text" name="search_matnr_descr" value="" placeholder="STAUFF Article Code/Désigniation" size="33" style="color:#ababab" required>
                                        </td>
                                        <td>
                                            <div style="width:100px;">
                                                <div id="button_globalsearch" style="display:block;">
                                                    <input type="submit" id="submitButton" value="Chercher"/>
                                                </div>
                                                <div id="sanduhr_globalsearch" style="display:none;">
                                                    <img id="sanduhr_globalsearch_img" src="{{asset('pictures/preloader.gif')}}" height="20" border="0">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr height="1">
                                        <td class="item">
                                            <div class="naviText">
                                                <a href="{{route('rechercheAvancee.affiche')}}" style="font-weight:bold;font-size:12px;color:#007857;text-decoration:underline;">
                                                    Recherche avancée
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </td>
                    <td align="center" style="width: 89px;background-color: #eeeeee; border-bottom: solid 5px #007857;; border-right: solid 1px #007857;; border-left: solid 1px #007857;; border-top: solid 1px #007857;">
                        <table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="center">
                                    <a @if(auth()->user()->acces_demande == 1) href="{{route('afficherequestlist')}}" @else onclick="return createAlert('Vous n\'êtes pas autorisé à faire des demandes')" href="#" @endif>
                                        <img border="0" src="{{asset('pictures/Button-Merkzettel-grau.jpg')}}" width="89" height="62">
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                @hasSection('requestList')
                                @yield('requestList')  <!-- <td align="center" valign="bottom" style="font-weight:normal;font-size:10px;background-color:#dddddd;"> -->
                                @else
                                <td align="center" valign="bottom" style="font-weight:normal;font-size:10px;">
                                    @endif

                                    Vos demandes<br/>

                                    (
                                    <span id="CountArticlesRequestList" style="color:red">
                                        @if(!session('requestList'))
                                            0
                                        @else
                                            {{count(session('requestList'))}}
                                        @endif
                                    </span>
                                    ) article(s)
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td align="center" style="width: 89px;background-color: #eeeeee; border-bottom: solid 5px #007857;; border-right: solid 1px #007857;; border-left: solid 1px #007857;; border-top: solid 1px #007857;">
                        <table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="center">
                                    <a @if(auth()->user()->acces_panier == 1) href="{{route('affichepanier')}}" @else onclick="return createAlert('Vous n\'êtes pas autorisé à utiliser le panier')" href="#" @endif>
                                        <img border="0" src="{{asset('pictures/Button-Einkaufswagen-grau.jpg')}}" width="89" height="62">
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                @hasSection('panier')
                                @yield('panier')  <!-- <td align="center" valign="bottom" style="font-weight:normal;font-size:10px;background-color:#dddddd;"> -->
                                @else
                                <td align="center" valign="bottom" style="font-weight:normal;font-size:10px;">
                                    @endif

                                    Panier<br/>

                                    (
                                    <span id="CountArticles" style="color:red">
                                        @if(!session('panier'))
                                            0
                                        @else
                                            {{count(session('panier'))}}
                                        @endif
                                     </span>
                                     ) article(s)

                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td class="green" colspan="4">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td id="pageSubline">Webshop</td>

                                <td width="749">
                                    <span style="font-weight: bold; color: #FFF; line-height: 25px;">
                                        &nbsp;&nbsp;
                                        @yield('titre')
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </div>

        <div id="main">
            <table id="mainTable" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td id="naviSub">

                        <div class="item bg20">
                            <a target="_blank" href="https://www.stauff.com/index.php?id=3886&L=1">STAUFF Clamps</a>
                        </div>

                        <div class="item bg26">
                            <a target="_blank" href="https://www.stauff.com/index.php?id=10360&L=1">STAUFF Connect</a>
                        </div>

                        <div class="item bg26">
                            <a target="_blank" href="https://www.stauff.com/index.php?id=4239&L=1">STAUFF Flanges</a>
                        </div>

                        <div class="item bg26">
                            <a target="_blank" href="https://www.stauff.com/index.php?id=10185&L=1">STAUFF Hose Connectors</a>
                        </div>

                        <div class="item bg26">
                            <a target="_blank" href="https://www.stauff.com/index.php?id=13089&L=1">STAUFF Quick Release Couplings</a>
                        </div>

                        <div class="item bg936">
                            <a target="_blank" href="https://www.stauff.com/index.php?id=4038&L=1">STAUFF Valves</a>
                        </div>

                        <div class="item bg22">
                            <a target="_blank" href="https://www.stauff.com/index.php?id=4201&L=1">STAUFF Test</a>
                        </div>

                        <div class="item bg25">
                            <a target="_blank" href="https://www.stauff.com/index.php?id=4238&L=1">STAUFF Diagtronics</a>
                        </div>

                        <div class="item bg23">
                            <a target="_blank" href="https://www.stauff.com/index.php?id=4034&L=1">STAUFF Filtration Technology</a>
                        </div>

                        <div class="item bg24">
                            <a target="_blank" href="https://www.stauff.com/index.php?id=4036&L=1">STAUFF Hydraulic Accessories</a>
                        </div>
                    </td>

                    <td id="pageContent">
                        <div id="content">
                            @yield('content')
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div id="foot">
            <div id="footPath">&nbsp;</div>
            <div class="green">
                <table id="pageFunctions" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td nowrap="">
                            <a href="" target="_blank">Imprint</a><img border="0" src="{{asset('pictures/bg_green.gif')}}" width="5" height="5">
                            &nbsp;<a href="" target="_blank">Conditions générales de vente</a><img border="0" src="{{asset('pictures/bg_green.gif')}}" width="5" height="5">
                            &nbsp;<a href="" target="_blank">Privacy Statement</a>
                            &nbsp;<a href="" >Contact</a>
                        </td>
                        <td id="naviFoot" align="center">
                            Production System (14/03/2019)&nbsp;

                        </td>
                        <td align="right" nowrap="">&copy; 2007-2019 STAUFF&nbsp;&nbsp;|&nbsp;&nbsp;All rights reserved.</td>

                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
    <meta http-equiv="expires" content="0">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="pragma" content="no-cache">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>STAUFF:&nbsp;Webshop</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('css/lukad.css')}}" />
    <link rel="shortcup icon" type="image/x-icon" href="{{ asset('pictures/favicon.ico')}}" />

    <script language="JavaScript1.2" type="text/javascript" src="{{ asset('js/page.js')}}"></script>
</head>

<body>
    <div id="root">
        <div id="head">

            <img id="logo" src="{{ asset('pictures/Banner-Stauffwebshop-oben.jpg')}}" alt="Walter Stauffenberg GmbH &amp; Co. KG"/>

            <table id="headTable" cellpadding="0" cellspacing="5" border="0" bordercolor="red">

                <tr>
                    <td class="green" colspan="4">
                        Your Single Source for Pipework Components and Hydraulic Accessories
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <img src="{{ asset('pictures/webshop-banner.jpg')}}" border="0">
                    </td>
                </tr>

                <tr>
                    <td class="green" colspan="4">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td id="pageSubline">Webshop</td>

                                <td width="749"><span style="font-weight: bold; color: #FFF; line-height: 25px;">&nbsp;&nbsp;Bienvenue</span></td>
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
                        <div class="itemActive bg20">Login</div>
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
                            <a href="" target="_blank">Imprint</a><img border="0" src="{{ asset('pictures/bg_green.gif')}}" width="5" height="5">

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
</body>
</html>

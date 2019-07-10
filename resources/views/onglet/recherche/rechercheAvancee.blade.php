@extends('layouts.afterAuth')

@section('titre', 'Recherche avancée')

@section('content')

<script language="JavaScript">
	function CheckSearchFormular ()
	{
		if ( document.f_directsearch.searchtype.value == 'filterinterchange' && document.f_directsearch.search_filterinterchange.value.length < 4)
		{
			alert('Please type at least 4 characters.');
			return false;
		}
		else
		{
			showWait('button_'+document.f_directsearch.searchtype.value,'sanduhr_'+document.f_directsearch.searchtype.value);
			return true;
		}
	}
	function CheckEnter (event, name)
	{
		if (event.keyCode==13)
		{
			document.f_directsearch.searchtype.value=name;
			if ( CheckSearchFormular() )
				document.f_directsearch.submit();
			return false;
		}
		return true;
	}
</script>


<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <form name="f_directsearch" action="{{route('rechercheAvancee.afficheresultat')}}" method="post" onsubmit="return CheckSearchFormular();">
                @csrf
                <input type="hidden" name="searchtype" id="searchtype" value="">
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
                                                        <img src="{{asset('pictures/Suche-STAUFF-gruen.jpg')}}" border="0">
                                                    </div>
                                                </td>
                                                <td>
                                                    <nobr>
                                                        <b>STAUFF Article Code/Désigniation </b>
                                                    </nobr>
                                                    <br/><br/>
                                                    Recherche&nbsp;
                                                    <img src="{{asset('pictures/info-icon.png')}}" border="0" onmouseover="showHelp(event,true,'Help_search_matnr_descr');" onmouseout="showHelp(event,false,'Help_search_matnr_descr');"/>
                                                    <br/>

                                                    <table border="0">
                                                        <tr>
                                                            <td>
                                                                <input type="text" name="search_matnr_descr" value="" size="20" maxlength="40" onkeypress="return CheckEnter(event,'matnr_descr');"/>
                                                            </td>
                                                            <td>
                                                                <div id="button_matnr_descr" style="display:block;">
                                                                    <input id="" type="submit" class="buttongreen" value="Rechercher" onClick="document.f_directsearch.searchtype.value='articleStauff'; return true;">
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
                                    <td class="col2" style="padding-left: 60px;">
                                        <div id="Help_search_custm" style="display: none; position:absolute; top:0px; left:0px; max-width: 300px;background-color: #ffffff; filter:Alpha(opacity=100);background-repeat:no-repeat; border:2px; border-color:#007857; border-style: solid;padding:5px;-moz-opacity: 1.0; opacity: 1.0; z-index: 99999;">
                                            Recherche via votre référence complète
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
                                                        <b>Référence client </b>
                                                    </nobr>
                                                    <br/><br/>
                                                    Recherche&nbsp;<img src="{{asset('pictures/info-icon.png')}}" border="0" onmouseover="showHelp(event,true,'Help_search_custm');" onmouseout="showHelp(event,false,'Help_search_custm');"/>
                                                    <br/>

                                                    <table border="0">
                                                        <tr>
                                                            <td>
                                                                <input type="text" name="search_custm" value="" size="20" maxlength="40" onkeypress="return CheckEnter(event,'custm');"/>
                                                            </td>
                                                            <td>
                                                                <div id="button_custm" style="display:block;">
                                                                    <input id="" type="submit" class="buttongreen" value="Rechercher" onClick="document.f_directsearch.searchtype.value='refClient'; return true;">
                                                                </div>
                                                                <div id="sanduhr_custm" style="display:none;">
                                                                    <img id="sanduhr_custm_img" src="{{asset('pictures/preloader.gif')}}" height="20" border="0">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="col1" style="">
                                        <div id="Help_search_filterinterchange" style="display: none; position:absolute; top:0px; left:0px; max-width: 300px;background-color: #ffffff; filter:Alpha(opacity=100);background-repeat:no-repeat; border:2px; border-color:#007857; border-style: solid;padding:5px;-moz-opacity: 1.0; opacity: 1.0; z-index: 99999;">
                                            Please enter at least four characters of the competitor’s description without any special characters like blanks or commas.
                                        </div>
                                        <table border="0">
                                            <tr>
                                                <td>
                                                    <div>
                                                        <img src="{{asset('pictures/CrossReference-STAUFF-gruen.jpg')}}" border="0">
                                                    </div>
                                                </td>
                                                <td>
                                                    <nobr>
                                                        <b>Cross Reference</b>
                                                    </nobr>
                                                    <br/><br/>
                                                    Recherche&nbsp;
                                                    <img src="{{asset('pictures/info-icon.png')}}" border="0" onmouseover="showHelp(event,true,'Help_search_filterinterchange');" onmouseout="showHelp(event,false,'Help_search_filterinterchange');"/>
                                                    <br/>

                                                    <table border="0">
                                                        <tr>
                                                            <td>
                                                                <input type="text" name="search_filterinterchange" value="" size="20" maxlength="40" onkeypress="return CheckEnter(event,'filterinterchange');"/>
                                                            </td>
                                                            <td>
                                                                <div id="button_filterinterchange" style="display:block;">
                                                                    <input id="" type="submit" class="buttongreen" value="Rechercher" onClick="document.f_directsearch.searchtype.value='filterinterchange'; return true;">
                                                                </div>
                                                                <div id="sanduhr_filterinterchange" style="display:none;">
                                                                    <img id="sanduhr_filterinterchange_img" src="{{asset('pictures/preloader.gif')}}" height="20" border="0">
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
                </table>
            </form>
        </td>
    </tr>
</table>

@endsection

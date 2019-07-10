<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
    <meta http-equiv="expires" content="0">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="pragma" content="no-cache">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/lukad.css')}}" />

    <script language="JavaScript">
		function aa()
		{
			var linecnt = document.linecnt;
			if ( linecnt )
			{
				if ( linecnt.linecnt.value < 8 )
					window.parent.document.getElementById("productdetails_text").height="180";
				else if ( linecnt.linecnt.value < 10 )
					window.parent.document.getElementById("productdetails_text").height="200";
				else if ( linecnt.linecnt.value < 15 )
					window.parent.document.getElementById("productdetails_text").height="250";
				else
					window.parent.document.getElementById("productdetails_text").height="300";
			}
		}
	</script>


</head>


<body  onload="aa(window);">
    @foreach($data['pere'] as $pere)
    <table width="100%" height="100%" border="0" cellpadding="2" cellspacing="0">
        <tr>
            <td align="left"><b>Stauff Mat. No.:</b></td>
            <td align="left">{{$pere->codePere}}</td>
        </tr>

        <tr>
            <td align="left"><b>Description:</b></td>
            <td align="left"><b>{{$pere->nomPere}}</b></td>
        </tr>

        <tr>
            <td align="left"><b>Conditionnement:</b></td>
            <td align="left">{{intval($pere->cond)}}</td>
        </tr>

        <tr>
            @if(!$data['fils'])
                @if(number_format($data['poidsTotal'], 4, ',', ' ') !== '0,0000')
                    <td align="left"><b>Poids:</b></td>
                    <td align="left">{{number_format($data['poidsTotal'], 4, ',', ' ')}} &nbsp; kg </td>
                @endif    
            @else
                @if($data['poidsTotal'] != 0)
                    <td align="left"><b>Poids:</b></td>
                    <td align="left">{{number_format($data['poidsTotal'], 4, ',', ' ')}}&nbsp; kg </td>
                @endif
            @endif

        </tr>

        <tr>
            <td align="left"><b>DEB:</b></td>
            <td align="left">{{$pere->deb}}</td>
        </tr>
        @if($pere->pays != 'CN')
            <tr>
                <td align="left"><b>Pays de production:</b></td>
                <td align="left">{{$pere->pays}}</td>
            </tr>
        @endif

    </table>
    @endforeach

    @if($data['fils'])
    <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="left"><b>Composants:</b></td>
        </tr>
        @foreach($data['fils'] as $fils)
        <tr>
            <td align="left">{{intval($fils->quantite)}} &nbsp; x &nbsp; {{$fils->codeFils}}</td>
            <td align="left">{{$fils->nomFils}}</td>
        </tr>
        @endforeach

    </table>
    @endif
<form name="linecnt"><input type="hidden" name="linecnt" value="{{$data['nbLine']}}"></form>

</body>

</html>

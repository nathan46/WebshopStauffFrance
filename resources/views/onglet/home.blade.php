@extends('layouts.afterAuth')

@section('titre')
Acceuil
@endsection

@section('acceuil')
<td id='acceuil' class="item active" style="border-right: solid 1px #007857;; border-left: solid 1px #007857;; border-top: solid 1px #007857;">
@endsection

@section('content')
<table class="twoCols" cellpadding="0" cellspacing="0" border="0">
    <tr>

        <td class="col1">

            <a href="" style="text-decoration: none">
                <img border="0" src="{{asset('pictures/ImFokus.jpg')}}" width="290" height="218" style="border: solid 1px #007857;">
            </a>

            <br/><br/><b>Product Focus</b><br/>STAUFF ACT Clamps - Efficient prevention of crevice corrosion under pipe clamps on stainless steel pipework, middle- and long-term cost savings due to extended service and maintenance intervals. For more information visit <a href="http://www.stauff.com/act" target="_blank">www.stauff.com/act</a>.

        </td>

        <td class="col2">

            <a href="clearancelist.cfm">
                <img border="0" src="{{asset('pictures/stauff-france.jpg')}}" width="290" height="290" style="border: solid 1px #007857;">
            </a>

        </td>


    </tr>
</table>
@endsection

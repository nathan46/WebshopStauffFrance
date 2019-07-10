@extends('layouts.afterAuth')

@section('titre','Suivi de commandes')

@section('content')

<form name="f_order" action="{{route('commande.affichecommande')}}" method="post" onsubmit="showWait('button_info','sanduhr_info');">
    @csrf
    <table cellspacing="5" cellpadding="2" border="0">
        <tr>
            <td>
                <b>Date (du)</b>
            </td>
            <td>
                <select name="fday">
                    @for($i = 1 ; $i < 32 ; $i++)
                        @if($i < 10)
                            <option value="{{$i}}" @if($dateDu->day === $i) selected @endif>0{{$i}}</option>
                        @else
                            <option value="{{$i}}">{{$i}}</option>
                        @endif
                    @endfor

                </select>
                /
                <select name="fmonth">
                    @for($i = 1 ; $i < 13 ; $i++)
                        @if($i < 10)
                            <option value="{{$i}}" @if($dateDu->month == $i) selected @endif>0{{$i}}</option>
                        @else
                            <option value="{{$i}}" @if($dateDu->month == $i) selected @endif>{{$i}}</option>
                        @endif
                    @endfor

                </select>
                /
                <select name="fyear">
                    @for($i = 2017 ; $i < $dateNow->year+1 ; $i++)
                        <option value="{{$i}}" @if($dateDu->year == $i) selected @endif>{{$i}}</option>
                    @endfor
                </select>
            </td>
            <td width="20"></td>
            <td align="middle">
                <b>Status</b>
            </td>
            <td>
                <select name="status">
                    <option value="T" @if($status === 'T') selected @endif>Toutes les commandes</option>
                    <option value="O" @if($status === 'O') selected @endif>En cours</option>
                    <option value="C" @if($status === 'C') selected @endif>Expédié</option>
                </select>
            </td>
            <td width="20"></td>
            <td>
                <b>Code STAUFF</b>
            </td>
            <td>
                <input type="text" name="itemcode" value="{{$itemCode}}" size="10"/>
            </td>
        </tr>
        <tr>
            <td>
                <b>Date (au)</b>
            </td>
            <td>
                <nobr>
                    <select name="tday">
                        @for($i = 1 ; $i < 32 ; $i++)
                            @if($i < 10)
                                <option value="{{$i}}" @if($dateAu->day == $i) selected @endif>0{{$i}}</option>
                            @else
                                <option value="{{$i}}" @if($dateAu->day == $i) selected @endif>{{$i}}</option>
                            @endif
                        @endfor

                    </select>
                    /
                    <select name="tmonth">
                        @for($i = 1 ; $i < 13 ; $i++)
                            @if($i < 10)
                                <option value="{{$i}}" @if($dateAu->month == $i) selected @endif>0{{$i}}</option>
                            @else
                                <option value="{{$i}}" @if($dateAu->month == $i) selected @endif>{{$i}}</option>
                            @endif
                        @endfor

                    </select>
                    /
                    <select name="tyear">
                        @for($i = 2017 ; $i < $dateNow->year+1 ; $i++)
                            <option value="{{$i}}" @if($dateAu->year == $i) selected @endif>{{$i}}</option>
                        @endfor
                    </select>
                </td>
                <td></td>
                <td>
                    <div style="width:100px;">
                        <div id="button_info" style="display:block;" align="middle">
                            <input id="" type="submit" class="buttongreen" value="Chercher">
                        </div>
                        <div id="sanduhr_info" style="display:none;" align="middle">
                            <img id="sanduhr_info_img" src="{{asset('pictures/preloader.gif')}}" height="20" border="0">
                        </div>
                    </div>
                </td>
                <td></td>
                <td></td>
                <td>
                    <b>Numéro de commande</b>
                </td>
                <td>
                    <input type="text" name="numcommande" value="{{$numCommande}}" size="10"/>
                </td>
            </tr>

            <tr>
                <td colspan="3" height="20"></td>
                @if(!$get)
                    <td align="middle">
                        <a href="{{asset('CSV/listCommand/'.$fileName)}}">
                            <input type="button" class="buttongreen" value="Export CSV">
                        </a>
                    </td>
                @endif
            </tr>
        </table>
    </form>


    @if(!$get)

    <table cellpadding="2" cellspacing="2" border="0" align="center">
        <tr bgcolor="#cccccc">
            <th title="numAr" align="middle" >
                N° d'AR
            </th>

            <th title="numCmd" align="middle" >
                N° de commande
            </th>

            <th title="codeClient" align="middle" >
                Code client
            </th>

            <th title="codeStf" align="middle" >
                Code STAUFF
            </th>

            <th title="description" align="middle" >
                Désigniation
            </th>

            <th title="quantite" align="middle" >
                Quantité
            </th>
            @if(auth()->user()->acces_prix == 1)
                <th title="prixUnit" align="middle" >
                    Prix unit.
                </th>
            @endif

            <th title="date" align="middle" >
                Date départ<span style="color: red">*</span>
            </th>

            <th title="status" align="middle" >
                Status
            </th>

        </tr>

        @foreach($commands as $command)
            @if(!isset($ancienAR))
                @php ($color = "#eeeeee")
                <tr bgcolor="{{$color}}">
            @else
                @if($ancienAR == $command->AR)
                <tr bgcolor="{{$color}}">
                @else
                    @if($color == "#eeeeee")
                        @php ($color = "#cecece")
                    @else
                        @php ($color = "#eeeeee")
                    @endif
                    <tr bgcolor="{{$color}}">
                @endif
            @endif
            <td>
                {{$command->AR}}
            </td>
            <td>
                {{$command->NumCommande}}
                @if($TEREX and $command->U_TEREX != '')
                    -&nbsp;{{$command->U_TEREX}}
                @endif
            </td>
            <td>
                {{$command->codeClient}}
            </td>
            <td>
                {{$command->ItemCode}}
            </td>
            <td>
                <b>{{$command->Nom}}</b>
            </td>
            <td align="right">
                {{intval($command->Quantite)}}
            </td>
            @if(auth()->user()->acces_prix == 1)
                <td align="right">
                    {{number_format($command->Prix, 4, ',', ' ')}}
                </td>
            @endif
            <td>
                <?php
                $s = new Carbon\Carbon($command->Date);
                if ($s->day == '1' and $s->month == '1') {
                    echo "<mark> A confirmer </mark>";
                } else {
                    echo $s->format('d/m/Y');
                }
                ?>
            </td>
            <td>
                {{$command->Status}}
            </td>
        </tr>
        @php ($ancienAR = $command->AR)
        @endforeach
        <tr>
            <td colspan="9">
                <br/><br/>
                <span style="color: red">* Date de départ prévue </span>
            </td>
        </tr>
    </table>

    @endif

    @endsection

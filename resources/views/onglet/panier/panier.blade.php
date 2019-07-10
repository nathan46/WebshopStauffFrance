@extends('layouts.afterAuth')

@section('titre','Panier')

@section('panier')
<td align="center" valign="bottom" style="font-weight:normal;font-size:10px;background-color:#dddddd;">
    @endsection

    @section('content')
    <script type="text/javascript">

        function toggleCompleteOrder(n)
        {
            var completelist = document.getElementsByName("CompleteOrder");
            if (completelist && completelist[0] )
            {
                for (i=1; i <= n; i++)
                {
                    var td = document.getElementById("td_complete" + i);
                    if (td)
                    {
                        if (completelist[0].checked)
                        {
                            td.style.display = "none";
                        }
                        else
                        {
                            td.style.display = "inline";
                        }
                    }
                }
            }
        }

        function multipleSuivant(type,index,condi){
            var nombre = parseInt(document.getElementById(type+index).value);
            var cond = parseInt(condi);
            if(nombre <= cond){
                document.getElementById(type+index).value = cond;
                saveInput(type,index);
            }else if (nombre > 100000 - cond) {
                document.getElementById(type+index).value = 100000 - cond;
                saveInput(type,index);
            }else {
                for (var i = 1; true; i++) {
                    if (i*cond >= nombre) {
                        document.getElementById(type+index).value = i*cond;
                        saveInput(type,index);
                        break;
                    }
                }
            }
            //saveInput(type,index);
        }

        function saveInput(type, index)
        {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            if(type == "complete"){
                var typeValue = $("#"+type+index).is(":checked");
            }else{
                var typeValue = document.getElementById(type+index).value;
            }

            var dataString = type+"="+typeValue+"&index="+index;
            //console.log(dataString);
            $.ajax({
                type: "POST",
                url: "savePanierInput",
                data: dataString,

                success: function(data){
                    //console.log(data);
                }
            });
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function(){

            @foreach($adresses as $adresse)
                @if($adresse->defaut == 'A')
                    @php($defaut = $adresse)
                @endif
            @endforeach


            var defaut = <?php echo json_encode($defaut) ?>;
            $("#shipToCode").val(defaut['nom']);
            $("#shipToStreet").val(defaut['adresse1']);
            $("#shipToCity").val(defaut['ville']);
            $("#shipToCounty").val(defaut['adresse2']);
            $("#shipToZipCode").val(defaut['codePostal']);

            $('#choixAdresse').change(function(){
                if( $("#choixAdresse").val() == '.'){
                    $("#infosAdresse").hide();
                    $("#autres").show();
                    $("#orderbutton").removeClass('buttongreen').addClass('buttongray');
                    $("#orderbutton").prop('disabled', true);
                }else{
                    var id = $(this).children(":selected").attr("id");
                    var adresses = <?php echo json_encode($adresses) ?>;
                    $("#shipToCode").val(adresses[id]['nom']);
                    $("#shipToStreet").val(adresses[id]['adresse1']);
                    $("#shipToCity").val(adresses[id]['ville']);
                    $("#shipToCounty").val(adresses[id]['adresse2']);
                    $("#shipToZipCode").val(adresses[id]['codePostal']);

                    $("#infosAdresse").show();
                    $("#autres").hide();
                    $("#orderbutton").removeClass('buttongray').addClass('buttongreen');
                    $("#orderbutton").prop('disabled', false);
                }
            });

            $('#orderbutton').click(function(){
                if($.trim($('#numCommande').val()) == ''){
                    alert('Vous devez renseigner un numéro de commande');
                }else{
                    $.confirm({
                        title: '<br/> Etes vous sûr de vouloir passer cette commande ?',
                        content: '',
                        buttons: {
                            confirm:{
                                text: 'oui',
                                btnClass: 'btn-stauff',
                                keys: ['enter'],
                                action: function(){
                                    document.f_basket.action.value = 'order';
                                    $('#f_basket').submit();
                                    console.log('lol');
                                }
                            },
                            cancel:{
                                text: 'non',
                            }
                        }
                    });
                }
            });

        });
    </script>

    @if(isset($panier))
    <form id="f_basket" name="f_basket" action="{{route('verifieretcommander')}}" method="post">
        @csrf

        <input type="hidden" name="action" value=""/>

        <table cellpadding="3" cellspacing="0" border="0" width="100%">
            <tr bgcolor="#cccccc">
                <th align="right" width="15">No.</th>

                <th style="border-left:2px solid #FFFFFF;" width="65">
                    Stauff Mat. No.
                </th>

                <th style="border-left:2px solid #FFFFFF;" width="125">
                    Client Mat. No.
                </th>

                <th style="border-left:2px solid #FFFFFF;" width="235">
                    Désigniation
                </th>

                <th style="border-left:2px solid #FFFFFF;" width="50">
                    Quantité
                </th>

                <th style="border-left:2px solid #FFFFFF;" width="65">
                    Prix net
                    <br/>
                    (EUR)
                </th>

                <th style="border-left:2px solid #FFFFFF;" width="75">
                    Montant
                    <br/>
                    (EUR)
                </th>

                <th style="border-left:2px solid #FFFFFF;" width="60">
                    Date de disponibilité
                </th>
                <th width="30">&nbsp;</th>
            </tr>

            <tr height="10">
                <td colspan="8"></td>
            </tr>

            @foreach($panier as $article)
                <tr bgcolor="#eeeeee">
                    <td align="right">
                        <font @if(!isset($listPrix)) color="red" @endisset>{{$loop->index + 1}}</font>
                    </td>

                    <td style="border-left:2px solid #FFFFFF;">
                        {{$article['itemCode']}}
                    </td>

                    <td style="border-left:2px solid #FFFFFF;" align="center">
                        @foreach($customerNo as $no)
                            @if($no->ItemCode === $article['itemCode'])
                                <b>{{$no->substitute}}</b>
                                @break
                            @endif
                        @endforeach
                    </td>

                    <td style="border-left:2px solid #FFFFFF;">
                        <b>{{$article['desc']}}</b>
                        <br/>
                    </td>

                    <td style="border-left:2px solid #FFFFFF;">
                        <input type="number" id="qty{{$loop->index}}" name="qty{{$loop->index}}" value="{{$article['qty']}}" style="width: 6em;" max="99999" @if(isset($cond[$loop->index])) onblur="multipleSuivant('qty','{{$loop->index}}',{{$cond[$loop->index]}})" step="{{$cond[$loop->index]}}" @else onblur="saveInput('qty','{{$loop->index}}')"  @endif @isset($order) readonly @endisset />
                    </td>

                    <td style="border-left:2px solid #FFFFFF;" align="center">
                        @if(isset($listPrix))
                            @if(auth()->user()->acces_prix == 1)
                                @if($listPrix[$loop->index] == true)
                                    {{number_format($listPrix[$loop->index],4,',',' ')}}
                                @else
                                    <b>Nous consulter</b>
                                    @php($nousConsulter = true)
                                @endif
                            @else
                                <span style="font-size: 20px">****</span>
                            @endif
                        @endif
                    </td>

                    <td style="border-left:2px solid #FFFFFF;" align='center'>
                        @if(isset($listPrix))
                            @if(auth()->user()->acces_prix == 1)
                                @if($listPrix[$loop->index] == true)
                                    {{number_format(floatval(str_replace(',','.',$listPrix[$loop->index])) * $article['qty'], 2, ',', ' ')}}
                                @endif
                            @else
                                <span style="font-size: 20px">****</span>
                            @endif
                        @endif
                    </td>

                    <td style="border-left:2px solid #FFFFFF;">
                        <span style="color:red">
                            @isset($listDate)
                                @php($date = $listDate[$loop->index])
                                @foreach($date['dates'] as $d)
                                    @if($date['qty'][$loop->index] !== 0)
                                        <nobr> {{$date['qty'][$loop->index]}} &nbsp; PC &nbsp;
                                    @endif
                                    @if($date['qty'][$loop->index] !== 0)
                                            @if($d !== false)
                                                le &nbsp; {{$d}} </nobr>
                                            @else
                                                <b> A confirmer </b></nobr>
                                            @endif
                                    @endif
                                @endforeach
                            @endisset
                        </span>
                    </td>

                    <td align="right">
                        <a href="{{route('affichepanier')}}?delete={{$loop->index}}" onClick="return confirm('Voulez vous supprimer cet article de votre panier ?');" title="Supprimer cet élément du panier">
                            <img src="{{asset('pictures/del.jpg')}}" border="0"/>
                        </a>
                    </td>
                </tr>
                <tr bgcolor="#eeeeee">

                    <td></td>

                    <td style="border-left:2px solid #FFFFFF;">

                    </td>

                    <td style="border-left:2px solid #FFFFFF;">

                    </td>

                    <td style="border-left:2px solid #FFFFFF;">
                        <table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td id="td_complete{{$loop->index+1}}">
                                    <input type="checkbox" id="complete{{$loop->index}}" name="complete{{$loop->index}}" onchange="saveInput('complete','{{$loop->index}}')" @if($article['complete'] === 'true') checked @endif />
                                    Livrer la ligne complète
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td style="border-left:2px solid #FFFFFF;color:red">
                        @isset($cond)
                            @isset($cond[$loop->index])
                                Par&nbsp;{{$cond[$loop->index]}}&nbsp;PC
                            @endisset
                        @endisset
                    </td>

                    <td style="border-left:2px solid #FFFFFF;">

                    </td>

                    <td style="border-left:2px solid #FFFFFF;">

                    </td>

                    <td colspan="2" style="color:red; border-left:2px solid #FFFFFF;">

                    </td>

                </tr>

                <tr height="10">
                    <td colspan="8"></td>
                </tr>
            @endforeach

            <tr height="10"></tr>
            @isset($listPrix)
                <tr bgcolor="#eeeeee">
                    <td></td>
                    <td colspan="2">
                        <b>Montant net H.T</b>
                        <br/>
                        <b>Poids total</b>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td colspan="3">
                        @if(auth()->user()->acces_prix == 1)
                            @if(isset($nousConsulter))
                                <b> Nous consulter </b>
                            @else
                                {{$prixTotal}}€
                            @endif
                        @else
                            <span style="font-size: 20px">****</span>
                        @endif
                        <br/>
                        @if($poidsTotal === 0)
                            <b> Poids indéterminé </b>
                        @else
                            {{$poidsTotal}}&nbsp;Kg
                        @endif
                    </td>

                </tr>

                <tr height="10"></tr>
            @endisset

            <tr bgcolor="#eeeeee">
                <td colspan="9" align="left">
                    <table border="0"  width="100%">
                        <tr>
                            <td></td>
                            <td align="left">
                                <input type="checkbox" name="CompleteOrder"  onclick="toggleCompleteOrder({{count($panier)}})" @if(isset($checked['all'])) checked @endif/>
                                Livrer la commande complète
                            </td>

                            <script type="text/javascript">
                                toggleCompleteOrder({{count($panier)}});
                            </script>

                            <td>
                                <input id="" type="submit" class="buttongreen" value="Vérifier prix et disponibilité" onClick="return BasketSubmit(2);">
                            </td>

                            <td align="right">
                                <input type="edit" id="numCommande" name="numCommande" value="" size="25" maxlength="35" placeholder="Numéro de commande" required @if(!isset($check)) disabled @endif  />
                            </td>

                            <td>
                                @if(isset($check))
                                    <input id="orderbutton" type="button" class="buttongreen" value="Commander">
                                @else
                                    <input id="orderbutton" type="submit" class="buttongray" value="Commander" onClick="alert('Vous devez vérifier le prix et la disponibilité avant de passer une commande'); return false;">
                                @endif
                            </td>

                            <td align="right">
                                &nbsp;
                                <a href="{{route('affichepanier')}}?delete=all" onClick="return confirm('Voulez vous vider entièrement votre panier ?');" title="Vider entièrement le panier">
                                    <img src="{{asset('pictures/del.jpg')}}" border="0"/>
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr height="10"></tr>

            <tr bgcolor="#eeeeee">
                <td colspan="9">
                    <table border="0" width="100%">
                        <tr>
                            <td><nobr>Choisir une adresse de livraison</nobr></td>
                        </tr>
                        <tr >
                            <td>
                                <select name="choixAdresse" id="choixAdresse" autocomplete="on" @if(!isset($check)) disabled @endif>
                                    @foreach($adresses as $adresse)
                                        <option id="{{$loop->index}}" name="option-{{$loop->index}}" value="{{$adresse->nom}}" @if($adresse->defaut === 'A') selected @endif>@if(trim($adresse->nom) !== '.') {{$adresse->nom}} @else Autres @endif</option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <table id="infosAdresse">
                                    <tr>
                                        <td>Nom</td>
                                        <td><textarea id="shipToCode" name="shipToCode" value="" cols="24" rows="2" style="resize: none; background-color: #eeeeee" required readonly></textarea></td>
                                    </tr>
                                    <tr>
                                        <td>Adresse 1</td>
                                        <td><textarea id="shipToStreet" name="shipToStreet" value="" cols="24" rows="2" style="resize: none; background-color: #eeeeee" required readonly></textarea></td>
                                    </tr>
                                    <tr>
                                        <td>Adresse 2</td>
                                        <td><textarea id="shipToCounty" name="shipToCounty" value="" cols="24" rows="2" style="resize: none; background-color: #eeeeee" readonly></textarea></td>
                                    </tr>
                                    <tr>
                                        <td>Ville</td>
                                        <td><textarea id="shipToCity" name="shipToCity" value="" cols="24" rows="1" style="resize: none; background-color: #eeeeee" required readonly></textarea></td>
                                    </tr>
                                    <tr>
                                        <td>Code postal</td>
                                        <td><textarea id="shipToZipCode" name="shipToZipCode" value="" cols="5" rows="1" style="resize: none; background-color: #eeeeee" required readonly></textarea></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td id="autres" style="display: none; font-size: 15px;"><br/><mark>Pour utiliser une autre adresse de livraison, veuillez nous contacter</mark></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </form>
    @else
    <div style="font-size:16px;">Il n'y a aucun produit dans votre panier.</div>
    @endif
    @endsection

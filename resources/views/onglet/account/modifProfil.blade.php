@extends('layouts.afterAuth')

@section('titre',"Modifier le profil d'un utilisateur")

@section('content')

    <style>
        .nomPrenom{
            font-size: 20px;
        }
        .imageDelete{
            position: relative;
            left: 50em;
        }
    </style>

    <script type="text/javascript">
        $(document).ready(function() {
            var par = $('div.slideDown');
            par.slideToggle('fast');
            $('.nomPrenom').click(function(e) {
                par.filter(':visible').add( $(this).closest('.menuPliant').find('div.slideDown')).slideToggle('slow');
                //e.preventDefault();
            });
        });
    </script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function checkModif(id){

            showWait('button_modif'+id,'sanduhr_modif'+id);

            var nom = $('#nom' + id).val();
            var prenom = $('#prenom' + id).val();
            var email = $('#email' + id).val() + "{{$fin}}";
            var telephone_fixe = $('#telephone_fixe' + id).val();
            var telephone_portable = $('#telephone_portable' + id).val();
            var fonction = $('#fonction' + id).val();
            var service = $('#service' + id).val();
            var token = $("#token").val();
            var suivi = $('#suivi' + id).is(":checked");
            var demande = $('#demande' + id).is(":checked");
            var panier = $('#panier' + id).is(":checked");
            var prix = $('#prix' + id).is(":checked");
            var condition = $('#condition' + id).is(":checked");

            $.ajax({
                type: "post",
                url:"{{URL::to('/monCompte/modifierProfil')}}",
                data:{nom:nom, prenom:prenom, email:email, telephone_fixe:telephone_fixe, telephone_portable:telephone_portable, fonction:fonction, service:service, currentId:id, acces_suivi:suivi, acces_demande:demande, acces_panier:panier, acces_prix:prix, acces_condition:condition, _token: token},
                dataType: 'json',              // let's set the expected response format
                success:function(data){
                    //console.log(data);
                    $('#error'+id).text("");
                    $('#success'+id).text("");
                    $('#success'+id).append("<strong>"+data.message+"</strong>");
                    stopShowWait('button_modif'+id,'sanduhr_modif'+id);
                },
                error: function (err) {
                    if (err.status == 422) { // when status code is 422, it's a validation issue
                    //console.log(err.responseJSON);
                    //$('#error'+id).fadeIn().html(err.responseJSON.errors);
                    $('#error'+id).text("");
                    $('#success'+id).text("");

                    // you can loop through the errors object and show it to the user
                    //console.warn(err.responseJSON.errors);
                    // display errors on each form field
                    $.each(err.responseJSON.errors, function (i, error) {
                        var el = $('#error'+id);
                        el.append("<strong>"+error[0]+'</strong><br/>');
                    });
                }
            }
        });
    }

    function deleteUserById(id){
        $.confirm({
            title: '<br/> Voulez vous supprimer définitivement cet utilisateur ?',
            content: '',
            draggable: false,
            buttons: {
                confirm:{
                    text: 'oui',
                    btnClass: 'btn-stauff',
                    keys: ['enter'],
                    action: function(){
                        var token = $("#token").val();

                        $.ajax({
                            type: "post",
                            url:"{{URL::to('/deleteUser')}}",
                            data:{currentId:id, _token: token},
                            dataType: 'json',

                            success: function(data){
                                //console.log(data);
                                location.reload();
                            }
                        });
                    }
                },
                cancel:{
                    text: 'non',
                }
            }
        });
    }

    </script>


    @foreach($listUser as $user)
        <div class="menuPliant">

            <table width='57%' align='left' border="0">
                <tr>
                    <td align='left' class="nomPrenom">
                        <span>
                            {{ucfirst(strtoupper($user->nom))}} {{ucfirst(strtolower($user->prenom))}}
                        </span>
                    </td>
                    <td align='right' style="vertical-align: middle">
                        <a href="#" id="deleteUser" title="Supprimer l'utilisateur" onclick="deleteUserById({{$user->id}}); return false;">
                            <img src="{{asset('pictures/del-blanc.jpg')}}" border="0" width='18px' />
                        </a>
                    </td>
                </tr>
            </table>
            <br/>
            <hr class="nomPrenom" width='57%' align='left'>
            <!--<span class="nomPrenom">
                {{ucfirst(strtoupper($user->nom))}} {{ucfirst(strtolower($user->prenom))}}
            </span>
            <img class="imageDelete" src="{{asset('pictures/del-blanc.jpg')}}" border="0" width='18px'/>
            <span class="nomPrenom">
                <br/>
                <hr width='57%' align='left'>
            </span> -->
            <div class="slideDown">

                <input type="hidden" name="currentId" value="{{$user->id}}"/>
                <input type="hidden" value="{{$fin}}" name="finEmail"/>
                <table>
                    <tr height="15px"></tr>
                    <tr>
                        <td style="font-size: 15px;">Nom<span style="color: red">*</span></td>
                        <td>
                            <input id="nom{{$user->id}}" type="text" class="form-control @error('nom') is-invalid @enderror" name="nom{{$user->id}}" value="{{$user->nom}}" required size="30" autofocus style="font-size: 15px">
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size: 15px;">Prénom<span style="color: red">*</span></td>
                        <td>
                            <input id="prenom{{$user->id}}" type="text" class="form-control @error('prenom') is-invalid @enderror" name="prenom{{$user->id}}" value="{{$user->prenom}}" required size="30" style="font-size: 15px">
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size: 15px;">Adresse Email<span style="color: red">*</span></td>
                        <td>
                            <input id="email{{$user->id}}" type="text" class="form-control @error('email') is-invalid @enderror" name="email{{$user->id}}" value="{{substr($user->email, 0, strpos($user->email, '@'))}}" required size="15" style="font-size: 15px"> <span style="font-size: 15px;">{{$fin}} </span>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size: 15px;">Téléphone fixe<span style="color: red">*</span></td>
                        <td>
                            <input id="telephone_fixe{{$user->id}}" type="text" class="form-control @error('telephone_fixe') is-invalid @enderror" name="telephone_fixe{{$user->id}}" value="{{$user->fixe}}" required size="30" style="font-size: 15px">
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size: 15px;">Téléphone portable</td>
                        <td>
                            <input id="telephone_portable{{$user->id}}" type="text" class="form-control @error('telephone_portable') is-invalid @enderror" name="telephone_portable{{$user->id}}" value="@isset($user->portable){{$user->portable}}@endisset" size="30" style="font-size: 15px">
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size: 15px;">Fonction<span style="color: red">*</span></td>
                        <td>
                            <input id="fonction{{$user->id}}" type="text" class="form-control @error('fonction') is-invalid @enderror" name="fonction{{$user->id}}" value="{{$user->fonction}}" required size="30" style="font-size: 15px">
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size: 15px;">Service<span style="color: red">*</span></td>
                        <td>
                            <input id="service{{$user->id}}" type="text" class="form-control @error('service') is-invalid @enderror" name="service{{$user->id}}" value="{{$user->service}}" required size="30" style="font-size: 15px">
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <br/>
                            <span style="color: red">* Champ obligatoire</span>
                        </td>
                    </tr>
                </table>

                <br/>

                <div id="error{{$user->id}}" style="color:red; font-size: 15px">

                </div>

                <div id='success{{$user->id}}' style="color:blue; font-size: 15px" >
                </div>


                <hr align='left' width='76%'>

                <table border="0" width="80%">
                    <thead>
                        <tr>
                            <td colspan="2" style="font-size: 17px">Autorisations :</td>
                        </tr>
                        <tr height="15px"></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-size: 15px">Possibilité de passer une commande/accéder au panier </td>
                            <td>
                                <label class="switch" align="right">
                                    <input class="switch-input" type="checkbox" id="panier{{$user->id}}" name="panier{{$user->id}}" @if($user->panier === '1') checked @endif />
                                    <span class="switch-label" data-on="oui" data-off="non"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 15px">Possibilité d'effectuer des demandes via la liste</td>
                            <td>
                                <label class="switch" align="right">
                                    <input class="switch-input" type="checkbox" id="demande{{$user->id}}" name="demande{{$user->id}}" @if($user->demande === '1') checked @endif/>
                                    <span class="switch-label" data-on="oui" data-off="non"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 15px">Accès aux conditions de vente</td>
                            <td>
                                <label class="switch" align="right">
                                    <input class="switch-input" type="checkbox" id="condition{{$user->id}}" name="condition{{$user->id}}" @if($user->condition === '1') checked @endif/>
                                    <span class="switch-label" data-on="oui" data-off="non"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 15px">Accès au suivi de commande</td>
                            <td>
                                <label class="switch" align="right">
                                    <input class="switch-input" type="checkbox" id="suivi{{$user->id}}" name="suivi{{$user->id}}" @if($user->suivi === '1') checked @endif/>
                                    <span class="switch-label" data-on="oui" data-off="non"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 15px">Accès aux prix</td>
                            <td>
                                <label class="switch" align="right">
                                    <input class="switch-input" type="checkbox" id="prix{{$user->id}}" name="prix{{$user->id}}" @if($user->prix === '1') checked @endif/>
                                    <span class="switch-label" data-on="oui" data-off="non"></span>
                                    <span class="switch-handle"></span>
                                </label>
                            </td>
                        </tr>

                    </tbody>
                </table>
                <div align="right">
                    <br/>
                    <input id="button_modif{{$user->id}}" type="button" class="buttongreen" value="Modifier le profil" onclick="checkModif('{{$user->id}}'); return false;">
                    <div id="sanduhr_modif{{$user->id}}" style="display:none;"><img id="sanduhr_modif{{$user->id}}_img" src="{{ asset('pictures/preloader.gif')}}" height="20" border="0"></div>
                </div>
            </div>
        </div>
    @endforeach


@endsection

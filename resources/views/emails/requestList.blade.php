
Objet : <b>{{$numCommande}}</b> du {{$date}} <br/><br/>

De : {{$user->nom}} {{$user->prenom}} - {{$user->email}} <br/><br/>

@foreach($requestList as $request)
    ---------------------------------------------------------- <br/><br/>

    Demande de&nbsp;
    @if(auth()->user()->acces_prix == 1)
        @if($request['prix'] === 'true')
            @if($request['delai'] === 'true')
                Prix/D&eacute;lai
            @else
                Prix
            @endif
        @elseif($request['delai'] === 'true')
            D&eacute;lai
        @endif
    @else
        Délai
    @endif

    &nbsp;-&nbsp;{{$request['qty']}}

    @isset($request['itemCode'])
        &nbsp;-&nbsp;{{$request['itemCode']}}&nbsp;-&nbsp;{{$request['desc']}}
    @endisset

    @isset($request['text'])
        @if(trim($request['text']) !== "")
            <br/><br/>
            Commentaire :<br/> {{$request['text']}}
        @endif
    @endisset


<br/><br/>

@endforeach

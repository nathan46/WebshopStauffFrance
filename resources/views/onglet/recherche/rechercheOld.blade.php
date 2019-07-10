@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __("Rechercher par numéro d'article") }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('recherche') }}">

                        @csrf
                        <div class="form-group row">
                            <label for="numero" class="col-md-4 col-form-label text-md-right">{{ __("Numéro d'article")}}</label>

                            <div class="col-md-6">
                                <input id="numero" type="text" class="form-control" name="numero" required autofocus>

                                @error('numero')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Rechercher') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

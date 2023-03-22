@extends('layouts.app')


@section('head')
    @pagestyle('appliance')
@endsection


@section('content')
    <main>
        <section>
        {{-- TODO: mettre le href de la croix --}}

            <img src="/public/icons/close-purple.svg" class="cross" alt=" ">
            <div class="appliance-header">
            <img src="{{ $company->logo }}" alt=" {{ $company->name }} logo ">
            <div class="appliance-title">
                <h3>{{ $internship['title'] }}</h3>
                <div> {{ $internship['duration'] }}-{{ $internship['city']->name }}-{{ $company->name }}
                </div>
            </div>
        </div>
        <div>
            <article>
Envoyer votre candidature composé de votre CV et lettre de motivation à l'adresse suivante : <a href= "mailto:{{$company->email}}">{{$company->email}}</a>.
            </article>
        </div>
        <div>
            <div class="validation"><input type="checkbox" class="checkbox" id="check" name="verification" value="true" > Avant de postuler, j'atteste que j'ai bien envoyé le mail de candidature à l'entreprise.
            </div>
        </div>
        <div>
            <button class="btn-primary">Valider ma candidature</button>
        </div>
        </section>
    </main>
@endsection

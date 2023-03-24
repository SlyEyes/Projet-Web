@php
    use Linkedout\App\Utils\TimeUtil;
@endphp

@extends('layouts.app')


@section('head')
    @pagestyle('appliance')
@endsection


@section('content')
    <main class="main">
        <section class="section section-sm">
            <img src="/public/icons/close-purple.svg" class="cross" alt="x" onclick="window.history.back()">
            <div class="appliance-header">
                <img src="{{ $company->logo }}" alt=" {{ $company->name }} logo">
                <div class="appliance-title">
                    <h3>{{ $internship['title'] }}</h3>
                    <div>
                        {{ $internship['duration'] }} - {{ $internship['city']->name }} - {{ $company->name }}
                    </div>
                </div>
            </div>

            @if ($appliance)
                Vous avez postulé cette offre le {{ TimeUtil::formatDateObject($appliance->applianceDate) }}.
            @else
                <div>
                    <article>
                        Envoyer votre candidature composée de votre CV et lettre de motivation à l'adresse suivante :
                        <a class="link" href="mailto:{{$company->email}}">{{$company->email}}</a>
                    </article>
                </div>

                <form id="validate-form" class="checkbox" method="post">
                    <input type="checkbox" class="checkbox" id="accept" name="accept" required>
                    <label for="accept">
                        Avant de postuler, j'atteste que j'ai bien envoyé le mail de candidature à l'entreprise.
                    </label>
                </form>

                <button form="validate-form" class="btn-primary">Valider ma candidature</button>
            @endif
        </section>
    </main>
@endsection

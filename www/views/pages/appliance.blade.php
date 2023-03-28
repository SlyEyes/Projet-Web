@php
    use Linkedout\App\utils\TimeUtil;
@endphp

@extends('layouts.app')


@section('head')
    @pagestyle('appliance')
    @pagescript('appliance')
    @componentscript('grade')
@endsection


@section('content')
    <main class="main">
        <section class="section section-sm">
            <div class="appliance-header">
                <img class="company-logo" src="{{ $company->logo }}" alt=" {{ $company->name }} logo">
                <div class="appliance-title">
                    <h3>{{ $internship->title }}</h3>
                    <div>
                        {{ TimeUtil::calculateDuration($internship->beginDate, $internship->endDate) }}
                        -
                        {{ $internship->city->name }}
                        -
                        {{ $company->name }}
                    </div>
                </div>
                <img src="/public/icons/close-purple.svg" class="cross" alt="x" onclick="window.history.back()">
            </div>

            @if($error)
                <div class="error">
                    {{ $error }}
                </div>
            @endif

            @if ($appliance && $appliance->validation)
                Ce stage a été validé par votre tuteur. Vous pouvez désormais le noter.
                <form method="post" id="rate-form">
                    @include('components.grade.select', [
                        'name' => 'rate',
                        'value' => !empty($rating) ? $rating->rating : null,
                    ])
                </form>
                <button class="btn btn-primary" form="rate-form" disabled>Valider la note</button>
            @elseif ($appliance && $appliance->applianceDate)
                <h3>Candidature</h3>

                <div>
                    Vous avez postulé cette offre le {{ TimeUtil::formatDateObject($appliance->applianceDate) }}.
                </div>

                @if ($appliance->responseDate)
                    <div>
                        Vous avez reçu une réponse de la part de l'entreprise
                        le {{ TimeUtil::formatDateObject($appliance->responseDate) }}.
                    </div>
                @else
                    <h3>Réponse de l'entreprise</h3>

                    <form id="response-form" class="checkbox" method="post">
                        <input type="checkbox" class="checkbox" id="response" name="response" required>
                        <label for="response">
                            J'ai reçu une réponse de la part de l'entreprise, qu'elle soit positive ou négative
                        </label>
                    </form>

                    <button form="response-form" class="btn-primary">Valider la réponse</button>
                @endif
            @else
                <div>
                    <article>
                        Envoyer votre candidature composée de votre CV et lettre de motivation à l'adresse suivante :
                        <a class="link" href="mailto:{{$company->email}}">{{$company->email}}</a>
                    </article>
                </div>

                <form id="validate-form" class="checkbox" method="post">
                    <input type="checkbox" class="checkbox" id="postulate" name="postulate" required>
                    <label for="postulate">
                        Avant de postuler, j'atteste que j'ai bien envoyé le mail de candidature à l'entreprise.
                    </label>
                </form>

                <button form="validate-form" class="btn-primary">Valider ma candidature</button>
            @endif
        </section>
    </main>
@endsection

@extends('layouts.app', ['title' => 'Profil - LinkedOut'])


@section('head')
    @pagestyle('profile')
@endsection


@section('content')
    <main class="main">
        <section class="section section-md">
            <h2>Profil</h2>

            <div class="profile-section">
                <h3 class="identity">
                    <span class="identity-logo">
                        {{ substr($person->firstName, 0, 1) }}
                    </span>
                    <span class="identity-name">
                        {{ $person->firstName }}
                        {{ $person->lastName }}
                    </span>
                </h3>

                <div class="profile-subsection">
                    <p>
                        Email : {{ $person->email }}
                    </p>

                    @switch($person->role->value)
                        @case('student')
                            <p>
                                Étudiant au campus de {{ $promotion->campus->name }}, promotion {{ $promotion->name }}
                            </p>
                            <p>
                                Tuteur : {{ $tutor->firstName }} {{ $tutor->lastName }}
                                (<a href="mailto:{{ $tutor->email }}" class="link">{{ $tutor->email }}</a>)
                            </p>
                            @break
                        @case('tutor')
                            <p>Tuteur</p>
                            @break
                        @case('administrator')
                            <p>Administrateur</p>
                            @break
                        @default
                            <p>Inconnu</p>
                    @endswitch
                </div>
            </div>

            @if (is_array($wishlist) && count($wishlist) > 0)
                <div class="profile-section">
                    <h3>Wishlist</h3>

                    <div class="internships-section">
                        @foreach ($wishlist as $wish)
                            <a href="/internship/{{ $wish->internship->id }}" class="internship-card">
                                <div>{{ $wish->internship->title }}</div>
                                <div class="small">
                                    {{ $wish->internship->companyName }},
                                    {{ $wish->internship->city->name }}
                                    <br/>
                                    Ajouté le {{ $wish->wishDate->format('d/m') }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (is_array($appliances) && count($appliances) > 0)
                <div class="profile-section">
                    <h3>Applications en cours</h3>

                    <div class="internships-section">
                        @foreach ($appliances as $appliance)
                            <a href="/internship/{{ $appliance->internship->id }}" class="internship-card">
                                <div>{{ $appliance->internship->title }}</div>
                                <div class="small">
                                    {{ $appliance->internship->companyName }},
                                    {{ $appliance->internship->city->name }}
                                    <br/>
                                    Postulé le {{ $appliance->applianceDate->format('d/m') }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="profile-section">
                <h3>Déconnexion</h3>

                <form method="post">
                    <input type="hidden" name="action" value="disconnect">
                    <button type="submit" class="btn btn-primary">Se déconnecter</button>
                </form>
            </div>
        </section>
    </main>
@endsection

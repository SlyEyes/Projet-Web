@extends('layouts.app', ['title' => 'Profil - LinkedOut'])


@section('head')
    @pagestyle('profile')
@endsection


@section('content')
    <main>
        <section>
            <h2>Profile</h2>

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

                    <p>
                        @switch($person->role->value)
                            @case('student')
                                Étudiant
                                @break
                            @case('tutor')
                                Tuteur
                                @break
                            @case('administrator')
                                Administrateur
                                @break
                            @default
                                Inconnu
                        @endswitch
                    </p>
                </div>
            </div>
        </section>
    </main>
@endsection

@extends('layouts.app')


@section('head')
    @pagestyle('internship')
    @pagescript('internship')
@endsection


@section('content')
    <main>
        <section>
            <img src="/public/icons/close-purple.svg" class="close-btn" alt="x" onclick="window.history.back()">

            <div class="internship-header">
                <img src="{{ $company->logo }}" alt="{{ $company->name }} logo">
                <div class="internship-title">
                    <h3>{{ $internship->title }}</h3>
                    <div class="internship-information">
                        {{ $formattedDuration }} -
                        {{ $internship->city->name }} -
                        <a class="link" href="/company/{{ $company->id }}">{{ $company->name }}</a>
                    </div>
                </div>
            </div>

            <div class="internship-description">
                <span class="bold">Description du stage</span>
                <p>{{ $internship->description }}</p>
            </div>

            <div class="internship-description">
                <span class="bold">Compétences requises</span>
                <p>{{ $internship->skills }}</p>
            </div>

            @if ($person->role->value == 'student')
                <div class="btn-row">
                    <button class="btn btn-primary {{ $appliance ? 'hidden' : '' }}" id="wishlist-add">
                        Ajouter à ma wishlist
                    </button>
                    <button class="btn btn-error {{ !$appliance ? 'hidden' : '' }}" id="wishlist-remove">
                        Retirer de ma wishlist
                    </button>
                    <a href="/internship/{{ $internship->id }}/apply">
                        <button class="btn btn-primary">Postuler</button>
                    </a>
                </div>
            @endif
        </section>
    </main>
@endsection

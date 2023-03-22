@extends('layouts.app')


@section('head')
    @pagestyle('error')
@endsection


@section('content')
    <main>
        <section>
            <h1>{{ $errorTitle ?? 'Erreur 🥲' }}</h1>
            <p>{{ $message }}</p>
            <a class="btn btn-primary" href="/">
                Retour à la page d'accueil
            </a>
        </section>
    </main>
@endsection

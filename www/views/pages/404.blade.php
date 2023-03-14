@extends('layouts.app')


@section('head')
    @pagestyle('404')
@endsection


@section('content')
    <main>
        <section>
            <h1>404 Not Found 🥲</h1>
            <p>Impossible de trouver la page recherchée ({{ $_SERVER['REQUEST_URI'] }})</p>
            <a class="btn btn-primary" href="/">
                Retour à la page d'accueil
            </a>
        </section>
    </main>
@endsection

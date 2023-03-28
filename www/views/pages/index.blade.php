@extends('layouts.app')


@section('head')
    @pagestyle('index')
@endsection


@section('content')
    <main class="main">
        <section>
            <h1>Le <span>meilleur</span> moyen de trouver son <span>stage</span> à CESI</h1>
            <form action="/search"  method="GET">
                <input type="text" name="q" placeholder="Entrez votre recherche de stage" required>
                <button type="submit" class="btn-primary">Rechercher</button>
            </form>
        </section>
    </main>
@endsection

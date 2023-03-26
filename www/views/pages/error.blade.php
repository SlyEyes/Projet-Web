@extends('layouts.app')


@section('head')
    @pagestyle('error')
    @if (!empty($trace))
        @pagescript('error')
    @endif
@endsection


@section('content')
    <main class="main">
        <section>
            <h1 id="error-header">{{ $errorTitle ?? 'Erreur 🥲' }}</h1>
            <p>{{ $message }}</p>
            @if(!empty($trace))
                <pre class="hidden" id="error-trace"><code>{{ $trace }}</code></pre>
            @endif
            <a class="btn btn-primary" href="/">
                Retour à la page d'accueil
            </a>
        </section>
    </main>
@endsection

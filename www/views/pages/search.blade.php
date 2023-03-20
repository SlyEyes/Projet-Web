@extends('layouts.app', ['title' => 'Rechercher - LinkedOut'])


@section('head')
    @pagestyle('search')
@endsection


@section('content')
    <main>
        <form action="/search" method="GET" id="search-form" title="Barre de recherche">
            <label for="search-bar">Barre de recherche</label>
            <input type="text"
                   id="search-form-input"
                   class="input-field"
                   name="s"
                   value="{{ $search ?? null }}"
                   placeholder="Rechercher un stage ou une entreprise ..."
                   required>

            <button type="submit" id="search-form-button" class="btn-primary">Rechercher</button>
        </form>
    </main>
@endsection

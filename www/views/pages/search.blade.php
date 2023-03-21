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
                   name="q"
                   value="{{  null  }}"
                   placeholder="Tapez le nom d'un stage ou d'une entreprise ..."
                   required>

            <button type="submit" id="search-form-button" class="btn-primary">Rechercher</button>
        </form>

        <section>
            <div id="filter-zone">

            </div>

            <ul id="results-list">

            </ul>
        </section>
    </main>
@endsection

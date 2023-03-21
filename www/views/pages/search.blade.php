@extends('layouts.app', ['title' => 'Rechercher - LinkedOut'])


@section('head')
    @pagestyle('search')
@endsection


@section('content')
    <main>
        <section>
            <div id="filter-zone">
                <h3>Filtres</h3>

                <form id="filters">
                    <div class="filters-research-type">
                        <input id="research-type-0" name="research-type" type="radio" value="internships">
                        <label for="research-type-0">Stages</label>
                    </div>

                    <div class="filters-research-type">
                        <input id="research-type-1" name="research-type" type="radio" value="companies">
                        <label for="research-type-1">Entreprises</label>
                    </div>
                </form>
            </div>

            <div id="search-zone">
                <form action="/search" method="GET" id="search-bar" title="Barre de recherche">
                    <label for="search-bar-input"></label>
                    <input type="text"
                           id="search-bar-input"
                           class="input-field"
                           name="q"
                           placeholder="Tapez le nom d'un stage ou d'une entreprise ..."
                           required>

                    <button type="submit" id="search-bar-button" class="btn-primary">Rechercher</button>
                </form>

                <ul id="results-list">
                    @foreach ($results as $result)
                        <li class="result">
                            <img src="{{ $result->logo }}" alt="Logo de {{ $result->name }}">
                            <div>
                                <h3>{{ $result->name }}</h3>
                                <p>Secteur {{ $result->sector }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    </main>
@endsection

@extends('layouts.app', ['title' => 'Rechercher - LinkedOut'])


@section('head')
    @pagestyle('search')
    @pagescript('search')
@endsection


@section('content')
    <main>
        <section>
            <div id="filter-zone">
                <h3>Filtres</h3>

                <form action="/search" method="GET" id="filters" title="Barre de recherches & Filtres">
                    <label for="filter-search-input"></label>
                    <input type="text"
                           id="filter-search-input"
                           name="q"
                           value="{{ $search ?? null }}"
                    >

                    <div class="filters-research-type">
                        <input id="research-target-0" name="target" type="radio" value="internships"
                               @if (isset($target) and $target == 'internships')
                                   checked
                               @endif
                               required
                        >
                        <label for="research-target-0">Stages</label>
                    </div>

                    <div class="filters-research-type">
                        <input id="research-target-1" name="target" type="radio" value="companies"
                               @if (isset($target) and $target == 'companies')
                                   checked
                               @endif
                               required
                        >
                        <label for="research-target-1">Entreprises</label>
                    </div>
                </form>
            </div>

            <div id="search-zone">
                <form id="search-bar" title="Barre de recherche">
                    <label for="search-bar-input"></label>
                    <input type="text"
                           id="search-bar-input"
                           class="input-field"
                           name="q"
                           value="{{ $search ?? null }}"
                           placeholder="Tapez le nom d'un stage ou d'une entreprise ..."
                           required
                    >

                    <button type="submit" id="search-bar-button" class="btn-primary" form="filters">Rechercher</button>
                </form>

                <ul id="results-list">
                    @if (isset($results) and $results != null)
                        @if ($target == 'internships')
                            @foreach ($results as $result)
                                <li class="result">
                                    <div>
                                        <h3>{{ $result->title }}</h3>
                                        <p>Posté par: {{ $results->companyName }}</p>
                                        <p>Le: {{ $results->offerDate }}</p>
                                        <p>Durée: du {{ $results->beginDate }} au {{ $results->endDate }}</p>
                                        <p>Skills: {{ $result->skills }}</p>
                                        <p>Nombre de places: {{ $results->numberPlaces }}</p>
                                    </div>
                                </li>
                            @endforeach

                        @elseif ($target == 'companies')
                            @foreach ($results as $result)
                                <li class="result">
                                    <img src="{{ $result->logo }}" alt="Logo de {{ $result->name }}">

                                    <div>
                                        <h3>{{ $result->name }}</h3>
                                        <p>Secteur: {{ $result->sector }}</p>
                                    </div>
                                </li>
                            @endforeach
                        @endif

                    @elseif (!isset($results))

                    @else
                        <li class="result">
                            <div>
                                <h3>Aucun résultat</h3>
                                <p>Vérifiez que vous avez bien tapé votre requête et que vous avez sélectionné
                                    les bons filtres</p>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </section>
    </main>
@endsection

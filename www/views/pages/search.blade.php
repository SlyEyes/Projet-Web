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
                           required
                    >

                    <h4>Cible</h4>

                    <div class="filters-research-type">
                        <input id="research-target-0" name="target" type="radio" value="internships"
                               required
                        >
                        <label for="research-target-0">Stages</label>
                    </div>

                    <div class="filters-research-type">
                        <input id="research-target-1" name="target" type="radio" value="companies"
                               required
                        >
                        <label for="research-target-1">Entreprises</label>
                    </div>

                    <h4>Durée</h4>

                    <div>
                        <input id="filter-duration-0" type="checkbox" value="one-to-three">
                        <label for="filter-duration-0">De 1 à 3 mois</label>
                    </div>

                    <div>
                        <input id="filter-duration-1" type="checkbox" value="three-to-six">
                        <label for="filter-duration-1">De 3 à 6 mois</label>
                    </div>

                    <div>
                        <input id="filter-duration-2" type="checkbox" value="six-to-nine">
                        <label for="filter-duration-2">De 6 à 9 mois</label>
                    </div>

                    <div>
                        <input id="filter-duration-3" type="checkbox" value="nine-to-twelve">
                        <label for="filter-duration-3">De 9 à 12 mois</label>
                    </div>
                </form>
            </div>

            <div>
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

                        <button type="submit" id="search-bar-button" class="btn-primary" form="filters">Rechercher
                        </button>
                    </form>

                    <ul id="results-list">
                        @if (isset($results) and $results != null)
                            @if ($target == 'internships')
                                @foreach ($results as $result)
                                    <li class="result">
                                        <a href="/internship/{{ $result->id }}" id="internship">
                                            <h3>{{ $result->title }}</h3>
                                            <p>Posté par: {{ $result->companyName }}</p>
                                            <p>Le: {{ $result->offerDate }}</p>
                                            <p>Durée: du {{ $result->beginDate }} au {{ $result->endDate }}</p>
                                            <p>Skills: {{ $result->skills }}</p>
                                            <p>Nombre de places: {{ $result->numberPlaces }}</p>
                                        </a>
                                    </li>
                                @endforeach

                            @elseif ($target == 'companies')
                                @foreach ($results as $result)
                                    <li class="result">
                                        <a href="/company/{{ $result->id }}" id="company">
                                            <img src="{{ $result->logo }}" alt="{{ $result->name }} logo">

                                            <div>
                                                <h3>{{ $result->name }}</h3>
                                                <p>Secteur: {{ $result->sector }}</p>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            @endif

                        @elseif (!isset($results))
                            <li class="result">
                                <div>
                                    <h3>Recherchez un stage ou une entreprise</h3>
                                    <p>Vous pouvez rechercher un stage ou une entreprise en utilisant la barre de
                                        recherche ci-dessus</p>
                                </div>
                            </li>
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

                <div id="page-zone">
                    <a class="btn-primary" href="{{ $url }}&page={{ $page - 1 }}">Page précédente</a>
                    <a class="btn-primary" href="{{ $url }}&page={{ $page + 1 }}">Page suivante</a>
                </div>
            </div>
        </section>
    </main>
@endsection

@php
    use Linkedout\App\utils\TimeUtil;
@endphp


@extends('layouts.app', ['title' => 'Rechercher - LinkedOut'])


@section('head')
    @pagestyle('search')
    @pagescript('search')
@endsection


@section('content')
    <main class="main">
        <section>
            <div id="filter-zone">
                <form action="/search" method="GET" id="filters" title="Barre de recherches & Filtres">
                    <input type="hidden"
                           id="filter-search-input"
                           name="q"
                           value="{{ $search ?? null }}"
                           required>

                    <h3>Filtres</h3>

                    <div class="filter-category">
                        <div class="bold">Cible</div>
                        <div class="checkbox">
                            <input id="research-target-0" name="target" type="radio" value="internships"
                                   required>
                            <label for="research-target-0">Stages</label>
                        </div>
                        <div class="checkbox">
                            <input id="research-target-1" name="target" type="radio" value="companies"
                                   required>
                            <label for="research-target-1">Entreprises</label>
                        </div>
                    </div>

                    <div class="filter-category">
                        <div class="bold">Durée</div>
                        <div class="checkbox">
                            <input id="filter-duration-0" type="checkbox" value="one-to-three">
                            <label for="filter-duration-0">De 1 à 3 mois</label>
                        </div>
                        <div class="checkbox">
                            <input id="filter-duration-1" type="checkbox" value="three-to-six">
                            <label for="filter-duration-1">De 3 à 6 mois</label>
                        </div>
                        <div class="checkbox">
                            <input id="filter-duration-2" type="checkbox" value="six-to-nine">
                            <label for="filter-duration-2">De 6 à 9 mois</label>
                        </div>
                        <div class="checkbox">
                            <input id="filter-duration-3" type="checkbox" value="nine-to-twelve">
                            <label for="filter-duration-3">De 9 à 12 mois</label>
                        </div>
                    </div>
                </form>
            </div>

            <div id="results-zone">
                <form id="search-bar" title="Barre de recherche">
                    <input type="text"
                           id="search-bar-input"
                           class="input-field"
                           name="q"
                           value="{{ $search ?? null }}"
                           placeholder="Tapez le nom d'un stage ou d'une entreprise ..."
                           required>

                    <button type="submit" id="search-bar-button" class="btn-primary" form="filters">
                        Rechercher
                    </button>
                </form>

                <div id="results-list">
                    @if (isset($results) and $results != null)
                        @if ($target == 'internships')
                            @foreach ($results as $result)
                                <a href="/internship/{{ $result->id }}" class="internship-result">
                                    <div>
                                        <div class="bold">{{ $result->title }}</div>
                                        <div class="small">
                                            {{ TimeUtil::calculateDuration($result->beginDate, $result->endDate) }}
                                            -
                                            {{ $result->city->name }}
                                            -
                                            {{ $result->companyName }}
                                        </div>
                                    </div>
                                    <div class="internship-description">
                                        {{ $result->description }}
                                    </div>
                                </a>
                            @endforeach

                        @elseif ($target == 'companies')
                            @foreach ($results as $result)
                                <a href="/company/{{ $result->id }}" class="company-result">
                                    <img src="{{ $result->logo }}" alt="{{ $result->name }} logo">

                                    <div class="company-information">
                                        <div class="bold">{{ $result->name }}</div>
                                        <div>
                                            {{ $result->sector }},
                                            {{ $result->internshipCount ?? 'aucun' }} stage
                                        </div>
                                    </div>
                                </a>
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
                                <p>
                                    Vérifiez que vous avez bien tapé votre requête et que vous avez sélectionné les
                                    bons filtres
                                </p>
                            </div>
                        </li>
                    @endif
                </div>

                <div id="page-zone">
                    <a class="btn-primary" href="{{ $url }}@if ($page != 1)&page={{$page - 1}}@endif">Précédent</a>
                    <p>Page {{ $page }}</p>
                    <a class="btn-primary" href="{{ $url }}&page={{ $page + 1 }}">Suivant</a>
                </div>
            </div>
        </section>
    </main>

    <button id="btn-filter" class="btn-primary">Filtres</button>
@endsection

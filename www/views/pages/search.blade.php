@extends('layouts.app', ['title' => 'Rechercher - LinkedOut'])


@section('head')
    @pagestyle('search')
@endsection


@section('content')
    <main>
        <form action="/search" method="GET">
            <h3>Rechercher</h3>

            <div class="form-element">
                <label for="form-search">Rechercher</label>
                <input type="text"
                       id="form-search"
                       name="search"
                       value="{{ $search ?? null }}"
                       required>
                >
            </div>

            <button type="submit">Rechercher</button>
        </form>
    </main>
@endsection

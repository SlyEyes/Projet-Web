@extends('layouts.app', ['title' => 'Connexion - LinkedOut'])


@section('head')
    @pagestyle('login')
@endsection


@section('content')
    <main>
        <form action="/login" method="POST">
            <h3>Connexion</h3>

            @isset($error)
                <div class="error">
                    {{ $error }}
                </div>
            @endisset

            <div class="form-element">
                <label for="form-email">Email</label>
                <input type="email"
                       id="form-email"
                       class="input-field"
                       name="email"
                       value="{{ $email ?? null }}"
                       required>
            </div>

            <div class="form-element">
                <label for="form-password">Mot de passe</label>
                <input type="password"
                       id="form-password"
                       class="input-field"
                       name="password"
                       required>
            </div>

            <button type="submit" class="btn-primary">Se connecter</button>
        </form>
    </main>
@endsection

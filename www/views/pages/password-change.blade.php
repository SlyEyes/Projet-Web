@extends('layouts.app', ['title' => 'Connexion - LinkedOut'])


@section('head')
    @pagestyle('password-change')
    @pagescript('password-change')
@endsection


@section('content')
    <main class="main">
        <form class="section section-xsm" action="/password-change{{ $redirect ? "?r=$redirect" : '' }}" method="POST">
            <h3>Changement de mot de passe</h3>

            @isset($error)
                <div class="error">
                    {{ $error }}
                </div>
            @endisset

            <p>Par mesure de sécurité, vous devez changer votre mot de passe</p>

            <div class="form-element">
                <label for="form-password">Nouveau de passe</label>
                <input type="password"
                       id="form-password"
                       class="input-field"
                       name="password"
                       minlength="8"
                       required>
            </div>

            <div class="form-element">
                <label for="form-password-confirm">Confirmer le mot de passe</label>
                <input type="password"
                       id="form-password-confirm"
                       class="input-field"
                       name="password-confirm"
                       minlength="8"
                       required>
            </div>

            <button type="submit" class="btn-primary">Se connecter</button>
        </form>
    </main>
@endsection

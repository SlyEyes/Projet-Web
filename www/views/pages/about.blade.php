@extends('layouts.app')


@section('head')
    @pagestyle('about')
@endsection


@section('content')
    <main class="main">
        <section class="section section-md">
            <h1>À propos</h1>

            <h3>Qui sommes-nous ?</h3>
            <p>
                Nous sommes un groupe d’indépendants qui a été chargé de créer un site Web pour votre école. Nous avons
                donc créé LinkedOut.
            </p>

            <h3>Qu’est-ce que LinkedOut ?</h3>
            <p>
                LinkedOut est un site web qui a pour but de vous aider à trouver un stage dans une entreprise en
                répertoriant des stages proposés par les entreprises à l'école.
            </p>

            <h3>Vous avez un problème ?</h3>
            <p>
                N'hésitez pas à contacter vos tuteurs ou à nous envoyer un mail à l'adresse suivante :
                <a href="mailto:contact@linked-out.fr "> contact@linked-out.fr </a>
            </p>

            <p><a href="/legal-notice">Mentions Légales </a></p>
        </section>
    </main>
@endsection

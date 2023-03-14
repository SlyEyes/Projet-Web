<header>
    <h3 class="nav-logo">
        <span>Linked</span><span class="purple">Out</span>
    </h3>

    <nav>
        <a href="/">Accueil</a>
        <a href="/search">Rechercher un stage</a>
        <a href="/about">À propos</a>
        @isset($person)
            <a href="/profile" class="nav-profile">
                <span class="nav-profile-logo">
                    {{ substr($person->firstName, 0, 1) }}
                </span>
                {{ $person->firstName }}
                {{ $person->lastName }}
            </a>
        @else
            <a href="/login" class="btn btn-primary">Connexion</a>
        @endisset
    </nav>
</header>

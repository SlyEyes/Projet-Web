<header>
    <h3 class="nav-logo">
        <a href="/">
            <span>Linked</span><span class="purple">Out</span>
        </a>
    </h3>

    <nav>
        <a href="/">
            <img src="/public/icons/home-black.svg" alt="Home">
            <span>Accueil</span>
        </a>
        <a href="/search" class="nav-search-desktop">
            <span>Rechercher un stage</span>
        </a>
        <a href="/search" class="nav-search-mobile">
            <img src="/public/icons/magnifying-black.svg" alt="Home">
            <span>Recherche</span>
        </a>
        <a href="/about">
            <img src="/public/icons/question-black.svg" alt="Home">
            <span>À propos</span>
        </a>

        @isset($person)
            <a href="/profile" class="nav-profile">
                <span class="nav-profile-logo">
                    {{ substr($person->firstName, 0, 1) }}
                </span>
                <span class="nav-profile-name">
                    {{ $person->firstName }}
                    {{ $person->lastName }}
                </span>
            </a>
        @else
            <a href="/login" class="btn btn-primary nav-login">
                <img src="/public/icons/user-black.svg" alt="Home">
                <span>Connexion</span>
            </a>
        @endisset
    </nav>
</header>

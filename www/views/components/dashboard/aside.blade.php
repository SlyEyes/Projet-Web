<aside>
    <h3>Tableau de bord</h3>
    <a class="{{ $collection == 'students' ? 'btn btn-primary' : '' }}" href="/dashboard/students">
        Étudiants
    </a>
    <a class="{{ $collection == 'tutors' ? 'btn btn-primary' : '' }}" href="/dashboard/tutors">
        Tuteurs
    </a>
    <a class="{{ $collection == 'administrators' ? 'btn btn-primary' : '' }}" href="/dashboard/administrators">
        Administrateurs
    </a>
    <a class="{{ $collection == 'internships' ? 'btn btn-primary' : '' }}" href="/dashboard/internships">
        Stages
    </a>
    <a class="{{ $collection == 'enterprises' ? 'btn btn-primary' : '' }}" href="/dashboard/enterprises">
        Entreprises
    </a>
</aside>

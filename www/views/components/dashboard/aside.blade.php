<aside>
    <div class="aside-container">
        <h3>Tableau de bord</h3>
        <a class="{{ $collection == 'students' ? 'btn btn-primary' : '' }}" href="/dashboard/students">
            Étudiants
        </a>
        @if($person->role->value == 'administrator')
            <a class="{{ $collection == 'tutors' ? 'btn btn-primary' : '' }}" href="/dashboard/tutors">
                Tuteurs
            </a>
            <a class="{{ $collection == 'administrators' ? 'btn btn-primary' : '' }}" href="/dashboard/administrators">
                Administrateurs
            </a>
        @endif
        <a class="{{ $collection == 'internships' ? 'btn btn-primary' : '' }}" href="/dashboard/internships">
            Stages
        </a>
        <a class="{{ $collection == 'companies' ? 'btn btn-primary' : '' }}" href="/dashboard/companies">
            Entreprises
        </a>
    </div>
</aside>


<button id="collections-menu" class="btn btn-primary">
    Dashboard
    @switch($collection)
        @case('students')
            (Étudiants)
            @break
        @case('tutors')
            (Tuteurs)
            @break
        @case('administrators')
            (Administrateurs)
            @break
        @case('internships')
            (Stages)
            @break
        @case('companies')
            (Entreprises)
            @break
    @endswitch
</button>

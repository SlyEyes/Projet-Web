<table>
    <thead>
    @switch($collection)
        @case('students')
        @case('tutors')
        @case('administrators')
            <tr>
                <th>Nom</th>
                <th>Email</th>
            </tr>
            @break
        @case('internships')
            <tr>
                <th>Titre</th>
                <th>Entreprise</th>
                <th class="sm-hide">Ville</th>
            </tr>
            @break
        @case('companies')
            <tr>
                <th>Nom</th>
                <th>Secteur</th>
            </tr>
            @break
    @endswitch
    </thead>

    <tbody>
    @switch($collection)
        @case('students')
        @case('tutors')
        @case('administrators')
            @foreach ($data as $row)
                <tr data-row-id="{{ $row->id }}">
                    <td>{{ $row->firstName }} {{ $row->lastName }}</td>
                    <td>{{ $row->email }}</td>
                </tr>
            @endforeach
            @break
        @case('internships')
            @foreach ($data as $row)
                <tr data-row-id="{{ $row->id }}">
                    <td>{{ $row->title }}</td>
                    <td>{{ $row->companyName }}</td>
                    <td class="sm-hide">{{ $row->city->name }}</td>
                </tr>
            @endforeach
            @break
        @case('companies')
            @foreach ($data as $row)
                <tr data-row-id="{{ $row->id }}">
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->sector }}</td>
                </tr>
            @endforeach
            @break
    @endswitch
    </tbody>
</table>

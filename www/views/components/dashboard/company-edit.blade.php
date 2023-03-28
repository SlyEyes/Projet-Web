<div class="field-group">
    <div>
        <label for="name">Nom</label>
        <input class="input-field" type="text" name="name" id="name" value="{{ $data->name ?? null }}" required>
    </div>
    <div>
        <label for="sector">Secteur</label>
        <input class="input-field" type="text" name="sector" id="sector" value="{{ $data->sector ?? null }}" required>
    </div>
</div>

<div class="field-group">
    <div>
        <label for="logo">Logo</label>
        <input class="input-field" type="url" name="logo" id="logo" value="{{ $data->logo ?? null }}" required>
    </div>

    <div id="logo-preview">
        <span>Prévisualisation du logo</span>
        <img src="{{ $data->logo ?? null }}" alt="Prévisualisation du logo de l'entreprise"/>
    </div>
</div>

<div class="field-group">
    <div>
        <label for="website">Site web</label>
        <input class="input-field" type="url" name="website" id="website" value="{{ $data->website ?? null }}" required>
    </div>

    <div>
        <label for="email">Email</label>
        <input class="input-field" type="email" name="email" id="email" value="{{ $data->email ?? null }}" required>
    </div>
</div>

<div class="field-group">
    <div>
        <label for="cesi-students">Étudiants CESI acceptés</label>
        <input class="input-field"
               type="number"
               min="0"
               name="cesi-students"
               id="cesi-students"
               value="{{ $data->cesiStudents ?? null }}"
               required>
    </div>

    <div>
        <label for="masked">Visibilité</label>
        <div class="checkbox">
            <input type="checkbox" name="masked" id="masked" {{ ($data->masked ?? false) ? 'checked' : '' }}>
            <label for="masked">Masqué</label>
        </div>
    </div>
</div>

<div class="field-group">
    <div>
        <label>Note de confiance</label>
        @include('components.grade.select', [
            'name' => 'trust-rating',
            'value' => $data->trustRating ?? null,
        ])
    </div>
</div>

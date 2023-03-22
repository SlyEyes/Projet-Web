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
        <label for="masked">Visibilité</label>
        <div class="checkbox">
            <input type="checkbox" name="masked" id="masked" {{ ($data->masked ?? false) ? 'checked' : '' }}>
            <label for="masked">Masqué</label>
        </div>
    </div>
</div>

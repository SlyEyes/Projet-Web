<div class="field-group">
    <div>
        <label for="title">Intitulé</label>
        <input class="input-field" type="text" name="title" id="title" value="{{ $data->title ?? null }}" required>
    </div>
    <div>
        <label for="salary">Salaire mensuel (€)</label>
        <input class="input-field" type="number" name="salary" id="salary" value="{{ $data->salary ?? null }}" required>
    </div>
</div>

<label for="description">Description</label>
<textarea class="input-field" name="description" id="description" required>{{ $data->description ?? null }}</textarea>

<div class="field-group">
    <div>
        <label for="begin-date">Date de début</label>
        <input class="input-field"
               type="date"
               name="begin-date"
               id="begin-date"
               value="{{ $data->beginDate ?? null }}"
               required>
    </div>
    <div>
        <label for="end-date">Date de fin</label>
        <input class="input-field"
               type="date"
               name="end-date"
               id="end-date"
               value="{{ $data->endDate ?? null }}"
               required>
    </div>
</div>

<div class="field-group">
    <div>
        <label for="places">Places disponibles</label>
        <input class="input-field"
               type="number"
               name="places"
               id="places"
               value="{{ $data->numberPlaces ?? null }}"
               required>
    </div>
    <div>
        <label for="masked">Visibilité</label>
        <div class="checkbox">
            <input type="checkbox" name="masked" id="masked" {{ ($data->checked ?? false) ? 'checked' : '' }}>
            <label for="masked">Masqué</label>
        </div>
    </div>
</div>

<div class="field-group">
    <div>
        <label for="zipcode">Code postal</label>
        <input class="input-field"
               type="number"
               id="zipcode"
               value="{{ $data->city->zipcode ?? null }}"
               required>
    </div>

    <div>
        <label for="city">Ville</label>
        {{-- TODO: prevent form submission if the input is disabled due to loading or error --}}
        <select class="input-field"
                type="text"
                name="cityId"
                id="city"
                {{ !$data ? 'disabled' : '' }}
                required>
            <option value="" disabled hidden {{ !$data ? 'selected' : '' }}>Sélectionnez une ville</option>
            @isset($data->city)
                <option value="{{ $data->city->id ?? null }}">{{ $data->city->name ?? null }}</option>
            @endisset
        </select>
    </div>
</div>

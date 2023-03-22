<div class="field-group">
    <div>
        <label for="lastname">Nom</label>
        <input class="input-field"
               type="text"
               name="lastname"
               id="lastname"
               value="{{ $data->lastName ?? null }}"
               required>
    </div>
    <div>
        <label for="firstname">Prénom</label>
        <input class="input-field"
               type="text"
               name="firstname"
               id="firstname"
               value="{{ $data->firstName ?? null }}"
               required>
    </div>
</div>

<div class="field-group">
    <div>
        <label for="email">Email</label>
        <input class="input-field" type="email" name="email" id="email" value="{{ $data->email ?? null }}" required>
    </div>

    @if($destination == 'new')
        <div>
            <label for="password">Mot de passe</label>
            <input class="input-field" type="password" name="password" id="password" required>
        </div>
    @endif
</div>

@if($collection == 'students')
    <div class="field-group">
        <div>
            <label for="campus">Campus</label>
            <select class="input-field" id="campus" required>
                <option value="" disabled selected>Choisissez un campus</option>
                @foreach($campuses as $campus)
                    <option value="{{ $campus->id }}"
                            {{ !empty($personPromotion) && $campus->id == $personPromotion->campus->id ? 'selected' : '' }}>
                        {{ $campus->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="promotion">Promotion</label>
            <select class="input-field"
                    name="promotion"
                    id="promotion"
                    required
                    {{ empty($personPromotion) ? 'disabled' : '' }}>
                <option value="" disabled selected>Choisissez une promotion</option>
                @foreach($promotions as $promotion)
                    <option value="{{ $promotion->id }}"
                            {{ !empty($personPromotion) && $promotion->id == $personPromotion->id ? 'selected' : '' }}>
                        {{ $promotion->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
@endif

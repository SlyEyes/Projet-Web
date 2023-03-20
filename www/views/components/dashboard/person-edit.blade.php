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

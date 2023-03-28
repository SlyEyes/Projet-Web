<div class="grade-select">
    @for($i = 0; $i < 5; $i++)
        @include('components.grade.star', [
            'id' => $i + 1,
        ])
    @endfor
    <input type="hidden" name="{{ $name ?? null }}"
           value="{{ $value ?? null }}">
</div>

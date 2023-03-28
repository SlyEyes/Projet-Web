<div class="grade-select">
    @for($i = 0; $i < 5; $i++)
        @include('components.grade.star', [
            'id' => $i + 1,
            'full' => $i + 1 <= $value
        ])
    @endfor
    <input type="hidden" name="{{ $name ?? null }}"
           value="{{ $value ?? null }}">
</div>

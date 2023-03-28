<div class="grade-display">
    @for($i = 0; $i < 5; $i++)
        @include('components.grade.star', [
            'id' => $i + 1,
            'full' => $i + 1 <= $value,
            'half' => $i + 1 > $value && $i + 0.5 <= $value,
        ])
    @endfor
</div>

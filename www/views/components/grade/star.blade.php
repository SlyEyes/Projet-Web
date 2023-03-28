<svg class="star" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" data-star-id="{{ $id ?? null }}">
    <path stroke-width="2" stroke="#765FF7" stroke-linecap="round" stroke-linejoin="round"
          d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
    <path d="M11.5 18V4L9 9L4 10L8 14L7 19.5L11.5 18Z" fill="#765FF7" class="{{ empty($half) ? 'hidden' : '' }}"/>
    <path d="M7 19.5L12 17.5L17 19.5L16.5 14L20.5 10L15 9L12 4L9 9L4 10L8 14L7 19.5Z" fill="#765FF7"
          class="star-full {{ empty($full) ? 'hidden' : '' }}"/>
</svg>

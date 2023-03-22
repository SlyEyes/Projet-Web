document.querySelector('#search-bar-input').addEventListener('input', function(event) {
    document.querySelector('#filter-search-input').value = event.target.value;
});
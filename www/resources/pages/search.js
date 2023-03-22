function filterInput(radio, value) {
    document.querySelector(radio).addEventListener('change', function() {
        if (this.checked) {
            document.querySelector('#filter-input').value = value;
        }
    });
}

filterInput('#research-type-0', 'internships');
filterInput('#research-type-1', 'companies');

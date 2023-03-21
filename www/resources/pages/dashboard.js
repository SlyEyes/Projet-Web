// Navigate to the corresponding dashboard page when a row is clicked
const handleRowClick = row => () => {
    const id = row.getAttribute('data-row-id');
    window.location = new URL(window.location.href + '/' + id);
};

document.querySelectorAll('tbody tr').forEach(row => {
    row.addEventListener('click', handleRowClick(row));
});


// Live preview of the logo in the company edit page
if (window.location.href.match(/\/companies\/(new|\d+)$/)) {
    const img = document.querySelector('#logo-preview img');
    const logoInput = document.getElementById('logo');

    logoInput.addEventListener('input', e => {
        img.src = e.target.value;
    });

    img.addEventListener('load', () => {
        logoInput.setCustomValidity('');
    });

    img.addEventListener('error', () => {
        logoInput.setCustomValidity('Image invalide');
    });
}

// Autocompletion of the city field in the internship edit page
if (window.location.href.match(/\/internships\/(new|\d+)$/)) {
    const citySelect = document.getElementById('city');
    const zipcodeInput = document.getElementById('zipcode');

    async function applyZipcodeSearch(zipcode, unselect = true) {
        if (unselect)
            citySelect.selectedIndex = 0;
        citySelect.disabled = true;

        if (zipcode.length !== 5)
            return;

        let cities;
        try {
            const res = await fetch(`/api/city/${zipcode}`);
            const { data } = await res.json();

            cities = data?.cities;
        } catch {
            return;
        }

        if (!cities || cities.length === 0)
            return;

        renewSuggestions(cities, !unselect);

        citySelect.disabled = false;
        if (cities.length === 1)
            citySelect.selectedIndex = 1;
    }

    function renewSuggestions(cities, keepSelection = false) {
        let selectedId = citySelect.value;

        [...citySelect.children].slice(1).forEach(option => {
            option.remove();
        });

        cities.forEach(city => {
            const option = document.createElement('option');
            option.value = city.id;
            option.textContent = city.name;
            citySelect.appendChild(option);
        });

        if (keepSelection)
            citySelect.value = selectedId;
    }

    zipcodeInput.addEventListener('input', e => applyZipcodeSearch(e.target.value));
    window.addEventListener('load', () => applyZipcodeSearch(zipcodeInput.value, false));
}

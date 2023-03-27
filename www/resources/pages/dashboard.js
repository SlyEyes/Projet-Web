// Navigate to the corresponding dashboard page when a row is clicked
const handleRowClick = row => () => {
    const id = row.getAttribute('data-row-id');
    window.location = new URL(window.location.href + '/' + id);
};

document.querySelectorAll('tbody tr').forEach(row => {
    row.addEventListener('click', handleRowClick(row));
});


// Toggle the visibility of the navigation popup on mobile
const collectionMenu = document.getElementById('collections-menu');
const aside = document.querySelector('aside');

collectionMenu.addEventListener('click', () => {
    aside.classList.toggle('active');
});

aside.addEventListener('click', e => {
    if (e.target === aside)
        aside.classList.remove('active');
});


// Autocomplete the promotion field in the student edit page
if (window.location.href.match(/\/students\/(new|\d+)$/)) {
    const campusSelect = document.getElementById('campus');
    const promotionSelect = document.getElementById('promotion');

    async function applyPromotionSearch(promotion) {
        promotionSelect.selectedIndex = 0;
        promotionSelect.disabled = true;

        let promotions;
        try {
            const res = await fetch(`/api/promotion/${promotion}`);
            const { data } = await res.json();

            promotions = data?.promotions;
        } catch {
            return;
        }

        renewSuggestions(promotions);

        promotionSelect.disabled = false;
        if (promotions.length === 1)
            promotionSelect.selectedIndex = 1;
    }

    function renewSuggestions(promotions) {
        let selectedId = promotionSelect.value;

        [...promotionSelect.children].slice(1).forEach(option => {
            option.remove();
        });

        promotions.forEach(promotion => {
            const option = document.createElement('option');
            option.value = promotion.id;
            option.textContent = promotion.name;
            promotionSelect.appendChild(option);
        });

        promotionSelect.value = selectedId;
    }

    campusSelect.addEventListener('change', e => applyPromotionSearch(e.target.value));
}


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


// Student years toggle for the internship edit page
if (window.location.href.match(/\/internships\/(new|\d+)$/)) {
    document.querySelectorAll('.pill').forEach(year => {
        year.addEventListener('click', e => {
            const input = e.target.querySelector('input');
            input.checked = !input.checked;
            year.classList.toggle('active');
        });
    });
}


// Tutor promotions toggle and promotions fetching for the tutor edit page
if (window.location.href.match(/\/tutors\/(new|\d+)$/)) {
    const campusSelect = document.getElementById('campus');
    const promotionsList = document.getElementById('tutor-promotions');
    const promotionsField = document.getElementById('tutor-promotions-field');

    const tutorId = window.location.href.match(/\/tutors\/(\d+)$/)?.[1];

    async function applyPromotionSearch(promotion) {
        let promotions;
        try {
            const res = await fetch(`/api/promotion/${promotion}?tutor=${tutorId || 'true'}`);
            const { data } = await res.json();

            if (data?.error)
                throw new Error(data.error);

            promotions = data?.promotions;
        } catch (e) {
            alert(`Une erreur est survenue: ${e.message}`);
            return;
        }

        promotionsField.classList.remove('hidden');
        document.querySelectorAll('#tutor-promotions .pill').forEach(pill => pill.remove());

        promotions.forEach(promotion => {
            const pill = document.createElement('div');
            pill.textContent = promotion.name;
            pill.classList.add('pill');
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = 'promotions[]';
            checkbox.value = promotion.id;
            pill.appendChild(checkbox);
            promotionsList.appendChild(pill);
        });

        applyPillsListener();
    }

    function applyPillsListener() {
        document.querySelectorAll('#tutor-promotions .pill').forEach(pill => {
            pill.addEventListener('click', e => {
                const input = e.target.querySelector('input');
                input.checked = !input.checked;
                pill.classList.toggle('active');
            });
        });
    }

    campusSelect.addEventListener('change', e => applyPromotionSearch(e.target.value));
    window.addEventListener('load', applyPillsListener);
}


// Prompt for delete for internship
if (window.location.href.match(/\/internships\/\d+$/)) {
    const deleteButton = document.getElementById('delete-btn');

    deleteButton.addEventListener('click', async e => {
        e.preventDefault();

        const { isConfirmed } = await Swal.fire({
            title: 'Êtes-vous sûr de vouloir supprimer ce stage ?',
            text: 'Cette action est irréversible. Il sera supprimé de toutes les wishlists.',
            icon: 'warning',
            iconColor: 'var(--red)',
            showCancelButton: true,
            confirmButtonColor: 'var(--red)',
            cancelButtonColor: 'var(--dark-gray)',
            confirmButtonText: 'Supprimer',
            cancelButtonText: 'Annuler',
            focusCancel: true,
        });

        if (isConfirmed)
            deleteButton.form.submit();
    });
}


// Check if the start date is before the end date and there is more than 1 month between them
if (window.location.href.match(/\/internships\/(new|\d+)$/)) {
    const beginDateInput = document.querySelector('input[name="begin-date"]');
    const endDateInput = document.querySelector('input[name="end-date"]');

    const checkDates = () => {
        const beginDate = new Date(beginDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (beginDate > endDate) {
            beginDateInput.setCustomValidity('La date de début doit être avant la date de fin');
            endDateInput.setCustomValidity('La date de fin doit être après la date de début');
        } else if (endDate - beginDate < 10 * 24 * 3600 * 1000) {
            beginDateInput.setCustomValidity('Le stage doit durer au moins deux semaines');
            endDateInput.setCustomValidity('Le stage doit durer au moins deux semaines');
        } else {
            beginDateInput.setCustomValidity('');
            endDateInput.setCustomValidity('');
        }
    };

    beginDateInput.addEventListener('input', checkDates);
    endDateInput.addEventListener('input', checkDates);
}


// Validate the appliances of the students
if (window.location.href.match(/\/students\/\d+$/)) {
    document.querySelectorAll('.student-appliance-validate').forEach(button => {
        button.addEventListener('click', async () => {
            const studentName = document.getElementById('firstname').value;
            Swal.fire({
                title: 'Êtes-vous sûr de vouloir valider ce stage ?',
                text: `Vous validez le fait que ${studentName} a bien été accepté pour ce stage. Il pourra alors le noter.`,
                icon: 'warning',
                iconColor: 'var(--red)',
                showCancelButton: true,
                confirmButtonColor: 'var(--violet)',
                cancelButtonColor: 'var(--dark-gray)',
                confirmButtonText: 'Valider',
                cancelButtonText: 'Annuler',
                focusCancel: true,
                showLoaderOnConfirm: true,
                allowOutsideClick: () => !Swal.isLoading(),
                async preConfirm() {
                    button.disabled = true;

                    const form = new FormData();
                    form.append('student', window.location.href.match(/\/students\/(\d+)$/)[1]);
                    form.append('internship', button.attributes['data-internship'].value);

                    const res = await fetch('/api/validate-appliance', {
                        method: 'POST',
                        body: form,
                    });
                    const body = await res.json();

                    if (body.error)
                        return Swal.fire({
                            title: 'Une erreur est survenue',
                            text: body.error,
                            icon: 'error',
                            iconColor: 'var(--red)',
                        });

                    window.location.reload();
                    await new Promise(resolve => setTimeout(resolve, 10000));
                },
            });
        });
    });
}

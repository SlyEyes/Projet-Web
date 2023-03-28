// Description: This file contains the javascript for the search page

let url = new URL(window.location.href);

// This is used to update the radio buttons in the filter form to match the target in the url
let target = url.searchParams.get('target');

let radioInternship = document.getElementById('research-target-0');
let radioCompany = document.getElementById('research-target-1');

if (target === 'internships' || target === null) {
    radioCompany.checked = false;
    radioInternship.checked = true;
} else if (target === 'companies') {
    radioInternship.checked = false;
    radioCompany.checked = true;
}

let filter = url.searchParams.get('f');
let filters = [];

for (let i = 0; i < 4; i++) {
    filters.push(document.getElementById('filter-duration-' + i));
}

function filterDuration(num) {
    filters.forEach((filter, index) => {
        if (index === num) {
            filter.checked = true;
        } else {
            filter.checked = false;
        }
    });
}

if (filter === '19' || filter === null) {
    filterDuration(0);
} else if (filter === '13') {
    filterDuration(1);
} else if (filter === '36') {
    filterDuration(2);
} else if (filter === '69') {
    filterDuration(3);
}


// This is used to update the hidden input in the filter form
document.querySelector('#search-bar-input').addEventListener('input', function (event) {
    document.querySelector('#filter-search-input').value = event.target.value;
});


// Page navigation
function navigateToPage(gap) {
    const page = parseInt(url.searchParams.get('page') || '1') + gap;
    url.searchParams.set('page', String(page));
    window.location.search = url.searchParams.toString();
}

document.querySelector('#pages-backward')?.addEventListener('click', function (e) {
    if (e.target.disabled)
        return;
    navigateToPage(-1);
});

document.querySelector('#pages-forward')?.addEventListener('click', function (e) {
    if (e.target.disabled)
        return;
    navigateToPage(1);
});


// This is used to toggle the filter form on mobile
let filterZone = document.querySelector('#filter-zone');

filterZone.addEventListener('click', function (ev) {
    if (ev.target === filterZone)
        filterZone.classList.remove('active');
});

document.querySelector('#btn-filter').addEventListener('click', function () {
    filterZone.classList.toggle('active');
});

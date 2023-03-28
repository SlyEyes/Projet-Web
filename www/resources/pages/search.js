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

let filterDuration0 = document.querySelector('#filter-duration-0');
let filterDuration1 = document.querySelector('#filter-duration-1');
let filterDuration2 = document.querySelector('#filter-duration-2');
let filterDuration3 = document.querySelector('#filter-duration-3');

function filterDuration($bool0 = false, $bool1 = false, $bool2 = false, $bool3 = false) {
    filterDuration0.checked = $bool0;
    filterDuration1.checked = $bool1;
    filterDuration2.checked = $bool2;
    filterDuration3.checked = $bool3;
}

if (filter === '19' || filter === null) {
    filterDuration(true, false, false, false);
} else if (filter === '13') {
    filterDuration(false, true, false, false);
} else if (filter === '36') {
    filterDuration(false, false, true, false);
} else if (filter === '69') {
    filterDuration(false, false, false, true);
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

// Description: This file contains the javascript for the search page

// This is used to update the radio buttons in the filter form to match the target in the url
let url = new URL(window.location.href);
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


// This is used to update the hidden input in the filter form
document.querySelector('#search-bar-input').addEventListener('input', function (event) {
    document.querySelector('#filter-search-input').value = event.target.value;
});


// This is used to toggle the filter form on mobile
let filterZone = document.querySelector('#filter-zone');

filterZone.addEventListener('click', function () {
    filterZone.classList.remove('active');
});

document.querySelector('#btn-filter').addEventListener('click', function () {
    filterZone.classList.toggle('active');
});

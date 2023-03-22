document.querySelector('#search-bar-input').addEventListener('input', function(event) {
    document.querySelector('#filter-search-input').value = event.target.value;
});

let url = new URL(window.location.href);
let target = url.searchParams.get('target');
if (target === 'internships' || target === null) {
    let radio = document.getElementById('research-target-0');
    radio.checked = true;
    radio.value = 'internships';
}
else if (target === 'companies') {
    let radio = document.getElementById('research-target-1');
    radio.checked = true;
    radio.value = 'companies';
}

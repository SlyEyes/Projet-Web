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

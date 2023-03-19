// Navigate to the corresponding dashboard page when a row is clicked
const handleRowClick = row => () => {
    const id = row.getAttribute('data-row-id');
    window.location = new URL(window.location.href + '/' + id);
};

document.querySelectorAll('tbody tr').forEach(row => {
    row.addEventListener('click', handleRowClick(row));
});

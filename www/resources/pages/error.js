// When the user clicks on the header, it toggles the hidden class on the stack trace
const header = document.getElementById('error-header');
const stackTrace = document.getElementById('error-trace');

header.addEventListener('click', () => {
    stackTrace?.classList.toggle('hidden');
});

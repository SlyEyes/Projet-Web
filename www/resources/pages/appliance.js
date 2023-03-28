const gradeSelect = document.querySelector('.grade-select');
const gradeValidate = document.querySelector('button[form="rate-form"]');
const gradeInput = gradeSelect?.querySelector('input[name="rate"]');

gradeSelect?.addEventListener('click', () => {
    const value = parseInt(gradeInput?.value);

    if (isNaN(value) || value < 1 || value > 5) {
        gradeValidate.disabled = true;
        return;
    }

    gradeValidate.disabled = false;
});

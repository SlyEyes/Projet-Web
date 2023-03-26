const password = document.getElementById('form-password');
const passwordConfirm = document.getElementById('form-password-confirm');

const passwordChange = () => {
    if (password.value !== passwordConfirm.value) {
        passwordConfirm.setCustomValidity('Les deux mots de passe ne correspondent pas.');
    } else {
        passwordConfirm.setCustomValidity('');
    }
};

password.addEventListener('change', passwordChange);
passwordConfirm.addEventListener('change', passwordChange);

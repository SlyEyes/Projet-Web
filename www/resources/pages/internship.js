const wishlistAddBtn = document.getElementById('wishlist-add');
const wishlistRemoveBtn = document.getElementById('wishlist-remove');
const internshipId = window.location.pathname.split('/')[2];

const sendChangeRequest = async action => {
    const res = await fetch(`/api/wishlist/${internshipId}`, {
        method: action === 'add' ? 'POST' : 'DELETE',
        credentials: 'include',
    });
    const data = await res.json();
    if (data.error)
        throw new Error(data.error);
};

wishlistAddBtn.addEventListener('click', () => {
    if (wishlistAddBtn.disabled)
        return;
    wishlistAddBtn.disabled = true;
    sendChangeRequest('add')
        .then(() => {
            wishlistRemoveBtn.classList.remove('hidden');
            wishlistAddBtn.classList.add('hidden');
            wishlistAddBtn.disabled = false;
        })
        .catch(err => {
            wishlistAddBtn.disabled = false;
            alert(err.message);
        });
});

wishlistRemoveBtn.addEventListener('click', () => {
    if (wishlistRemoveBtn.disabled)
        return;
    wishlistRemoveBtn.disabled = true;
    sendChangeRequest('remove')
        .then(() => {
            wishlistAddBtn.classList.remove('hidden');
            wishlistRemoveBtn.classList.add('hidden');
            wishlistRemoveBtn.disabled = false;
        })
        .catch(err => {
            wishlistRemoveBtn.disabled = false;
            alert(err.message);
        });
});

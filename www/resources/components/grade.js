function setSelectValue(select, value) {
    select
        .querySelectorAll('.grade-select .star .star-full')
        .forEach(star => star.classList.add('hidden'));

    for (let i = 1; i <= value; i++)
        select
            .querySelector(`.grade-select .star[data-star-id="${i}"] .star-full`)
            .classList
            .remove('hidden');

}

document.querySelectorAll('.grade-select .star').forEach(star => {
    star.addEventListener('click', () => {
        const select = star.closest('.grade-select');
        const starId = star.attributes['data-star-id'].value;
        select.querySelector('input').value = starId;
        setSelectValue(select, starId);
    });

    star.addEventListener('mouseover', () => {
        const select = star.closest('.grade-select');
        const starId = star.attributes['data-star-id'].value;
        setSelectValue(select, starId);
    });

    star.addEventListener('mouseout', () => {
        const select = star.closest('.grade-select');
        const value = select.querySelector('input').value;
        setSelectValue(select, value);
    });
});

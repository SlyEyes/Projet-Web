const offline = window.localStorage.getItem('offline');

if (offline) {
    const { person, wishlist, appliances, internships } = JSON.parse(offline);

    document.querySelector('section').remove();

    document.querySelector('main').innerHTML = `
        <section class="section section-md">
            <h2>Bonjour ${person.firstName}</h2>
            ${wishlist && wishlist.length > 0 ? `
                <h3>Ma wishlist</h3>
                <div class="list">
                    ${displayCards(wishlist)}
                </div>
            ` : ''}
            ${appliances && appliances.length > 0 ? `
                <h3>Mes applications</h3>
                <div class="list">
                    ${displayCards(appliances)}
                </div>
            ` : ''}
            ${internships && internships.length > 0 ? `
                <h3>Mes stages</h3>
                <div class="list">
                    ${displayCards(internships)}
                </div>
            ` : ''}
            <h3>Hors ligne</h3>
            <div>
                <button class="btn btn-primary" onclick="window.location.reload()">Rafraîchir la page</button>
            </div>
        </section>
    `;
}

function displayCards(items) {
    return items.map(item => `
            <div class="card">
                <div>${item.internship.title}</div>
                <div>
                    <div class="small">${item.internship.companyName} - ${item.internship.city.name}</div>
                    <div class="small">
                        Du ${(new Date(item.internship.beginDate)).toLocaleDateString()}
                        au ${(new Date(item.internship.endDate)).toLocaleDateString()}
                    </div>
                </div>
            </div>
        `).join('');
}

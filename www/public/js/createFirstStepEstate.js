let tg = window.Telegram.WebApp;
tg.expand();

document.getElementById('username').value = tg.initDataUnsafe.user.username;
document.getElementById('user_id').value = tg.initDataUnsafe.user.id;
document.getElementById('first_name').value = tg.initDataUnsafe.user.first_name;
document.getElementById('initData').value = tg.initData;
document.getElementById('last_name').value = tg.initDataUnsafe.user.last_name;

tg.enableClosingConfirmation();

let form = document.getElementById('form');

form.addEventListener('submit', (e) => {
    const elems = [
        'photo-error',
        'description-error',
        'deal_type-error',
        'bathrooms-error',
        'bedrooms-error',
        'conditioners-error',
        'house_type_id-error'
    ];
    elems.forEach((elem) => {
        document.getElementById(elem).innerText = "";
    })

    document.getElementById('btn-submit').disabled = true;

    e.preventDefault();
    const formData = new FormData(e.currentTarget);

    fetch(`https://13f0-77-106-104-230.ngrok-free.app/estate/`, {
        headers: {
            Accept: "application/json"
        },
        method: "POST",
        body: formData,

    })
        .then((response) => response.json())
        .then((json) => {
            document.getElementById('btn-submit').disabled = false;

            for (let error in json?.errors) {
                document.getElementById(`${error}-error`).innerText = json.errors.error[0];
            }
        })
})

function changeTypePrice(deal_type) {
    switch(deal_type) {
        case 'Продажа':
            document.getElementById('price-container').classList.remove('d-none');
            document.getElementById('period-container').classList.add('d-none');
            document.getElementById('period_price-container').classList.add('d-none');
            break;

        case 'Аренда':
            document.getElementById('price-container').classList.add('d-none');
            document.getElementById('period-container').classList.remove('d-none');
            document.getElementById('period_price-container').classList.remove('d-none');
            break;
    }
}

document.getElementById('Продажа').addEventListener("change", () => {
    changeTypePrice('Продажа');
})
document.getElementById('Аренда').addEventListener("change", () => {
    changeTypePrice('Аренда');
})


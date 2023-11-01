let tg = window.Telegram.WebApp;
tg.expand();

const ID_ESTATE = window.location.href.match(/\/estate\/(\d+)/)[1];
document.getElementById('username').value = tg.initDataUnsafe.user.username;
document.getElementById('user_id').value = tg.initDataUnsafe.user.id;
document.getElementById('first_name').value = tg.initDataUnsafe.user.first_name;
document.getElementById('initData').value = tg.initData;
document.getElementById('last_name').value = tg.initDataUnsafe.user.last_name;

let form = document.getElementById('form');

form.addEventListener('submit', (e) => {
    const elems = [
        'main-photo-error',
        'photo-error',
        'video-error',
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

    fetch(`https://8588-77-106-104-230.ngrok-free.app/estate/${ID_ESTATE}?_method=PATCH`, {
        headers: {
            Accept: "application/json"
        },
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((json) => {
            document.getElementById('btn-submit').disabled = false;

            console.log(json);
            if (!json?.errors) {
                tg.close();
            }

            for (let error in json?.errors) {
                document.getElementById(`${error}-error`).innerText = json.errors[error][0];
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

if (document.getElementById('Продажа').checked) {
    changeTypePrice('Продажа');
} else if (document.getElementById('Аренда').checked) {
    changeTypePrice('Аренда');
}

document.getElementById('Продажа').addEventListener("change", () => {
    changeTypePrice('Продажа');
})
document.getElementById('Аренда').addEventListener("change", () => {
    changeTypePrice('Аренда');
})

let collageMain = document.getElementById('collage-main') ?? false;
let collageExtra = document.getElementById('collage-extra') ?? false;

if (collageMain) {
    document.getElementById('main_photo').addEventListener('change', () => {
        collageMain.classList.add('d-none');
    })
}

if (collageExtra) {
    document.getElementById('photo').addEventListener('change', () => {
        collageExtra.classList.add('d-none');
    })
}


import {setErrorTextField, setDataValue} from "./shared.js";
import {ERROR_TEXT_FIELDS} from "./variables.js";

let tg = window.Telegram.WebApp;
const initDataUnsafe = tg.initDataUnsafe.user;
tg.expand();

setDataValue('username', initDataUnsafe.username)
setDataValue('user_id', initDataUnsafe.id)
setDataValue('first_name', initDataUnsafe.first_name)
setDataValue('initData', tg.initData)
setDataValue('last_name', initDataUnsafe.last_name)

tg.enableClosingConfirmation();

let form = document.getElementById('form');

form.addEventListener('submit', (event) => {
    event.preventDefault();
    document.getElementById('btn-submit').disabled = true;

    for (let errorTextField of ERROR_TEXT_FIELDS) {
        setErrorTextField(errorTextField, "");
    }

    const formData = new FormData(event.currentTarget);

    fetch(`https://a811-37-21-168-91.ngrok-free.app/estate/`, {
        headers: {
            Accept: "application/json"
        },
        method: "POST",
        body: formData,

    })
        .then((response) => response.json())
        .then((json) => {
            document.getElementById('btn-submit').disabled = false;

            for (let error of json?.errors) {
                if (error.photo) setErrorTextField('photo-error', error.photo[0]);
                if (error.description) setErrorTextField('description-error', error.description[0]);
                if (error.deal_type) setErrorTextField('deal_type-error', error.deal_type[0]);
                if (error.bathrooms) setErrorTextField('bathrooms-error', error.bathrooms[0]);
                if (error.bedrooms) setErrorTextField('bedrooms-error', error.bedrooms[0]);
                if (error.conditioners) setErrorTextField('conditioners-error', error.conditioners[0]);
                if (error.house_type_id) setErrorTextField('house_type_id-error', error.house_type_id[0]);
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

let collage = document.getElementById('collage') ?? false;

if (collage) {
    document.getElementById('photo').addEventListener('change', (event) => {
        collage.classList.add('d-none');
    })
}

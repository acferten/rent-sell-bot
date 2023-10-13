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
    const elems = ['photo-error', 'description-error', 'country-error', 'street-error', 'district-error', 'town-error', 'deal_type-error', 'bathrooms-error', 'bedrooms-error', 'conditioners-error', 'house_type_id-error'];
    elems.forEach((elem) => {
        document.getElementById(elem).innerText = "";
    })

    e.preventDefault();
    const formData = new FormData(e.currentTarget);

    fetch("https://cfe8-79-136-237-88.ngrok-free.app/estate", {
        headers: {
            Accept: "application/json"
        },
        method: "POST",
        body: formData,

    })
        .then((response) => response.json())
        .then((json) => {
            if (json?.errors?.photo) document.getElementById('photo-error').innerText = json.errors.photo[0];
            if (json?.errors?.description) document.getElementById('description-error').innerText = json.errors.description[0];
            if (json?.errors?.deal_type) document.getElementById('deal_type-error').innerText = json.errors.deal_type[0];
            if (json?.errors?.bathrooms) document.getElementById('bathrooms-error').innerText = json.errors.bathrooms[0];
            if (json?.errors?.bedrooms) document.getElementById('bedrooms-error').innerText = json.errors.bedrooms[0];
            if (json?.errors?.conditioners) document.getElementById('conditioners-error').innerText = json.errors.conditioners[0];
            if (json?.errors?.house_type_id) document.getElementById('house_type_id-error').innerText = json.errors.house_type_id[0];
        });
})



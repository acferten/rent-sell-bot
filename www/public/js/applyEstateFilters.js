let tg = window.Telegram.WebApp;
tg.expand();

document.getElementById('username').value = tg.initDataUnsafe.user.username;
document.getElementById('user_id').value = tg.initDataUnsafe.user.id;
document.getElementById('first_name').value = tg.initDataUnsafe.user.first_name;
document.getElementById('last_name').value = tg.initDataUnsafe.user.last_name;
document.getElementById('initData').value = tg.initData;
// dynamic location

const countrySelect = document.getElementById('country');
const stateSelect = document.getElementById('state');
const countySelect = document.getElementById('county');
const townSelect = document.getElementById('town');

fetch(`${NGROK_URL}/api/countries`, {
    headers: {
        Accept: "application/json"
    },
    method: "GET",
}).then(response => response.json()).then(countries => {
    const countrySelect = document.getElementById('country');
    countries.forEach((country) => {
        let option = document.createElement('option');
        option.value = country;
        option.innerText = country;
        countrySelect.appendChild(option);
    })
})

countrySelect.addEventListener('change', (event) => {
    if (!event.target.value) {
        showSelects(countrySelect, 'state-group');
        showSelects(false, 'county-group');
        showSelects(false, 'town-group');
        stateSelect.innerHTML = "<option value='' selected>Выберите район</option>";
        countySelect.innerHTML = "<option value='' selected>Выберите округ</option>";
        townSelect.innerHTML = "<option value='' selected>Выберите город</option>";
        return;
    }
    fetch(`${NGROK_URL}/api/countries/${countrySelect.value}/states`, {
        headers: {
            Accept: "application/json"
        },
        method: "GET",
    }).then(response => response.json()).then(states => {
        showSelects(countrySelect, 'state-group');
        showSelects(false, 'county-group');
        showSelects(false, 'town-group');
        stateSelect.innerHTML = "<option value='' selected>Выберите район</option>";
        countySelect.innerHTML = "<option value='' selected>Выберите округ</option>";
        townSelect.innerHTML = "<option value='' selected>Выберите город</option>";
        states.forEach((state) => {
            let option = document.createElement('option');
            option.value = state;
            option.innerText = state;
            stateSelect.appendChild(option);
        })
    })
})

stateSelect.addEventListener('change', (event) => {
    if (!event.target.value) {
        showSelects(stateSelect, 'county-group');
        showSelects(false, 'town-group');
        countySelect.innerHTML = "<option value='' selected>Выберите округ</option>";
        townSelect.innerHTML = "<option value='' selected>Выберите город</option>";
        return;
    }
    fetch(`${NGROK_URL}/api/countries/${countrySelect.value}/states/${stateSelect.value}/counties`, {
        headers: {
            Accept: "application/json"
        },
        method: "GET",
    }).then(response => response.json()).then(counties => {
        showSelects(stateSelect, 'county-group');
        showSelects(false, 'town-group');
        countySelect.innerHTML = "<option value='' selected>Выберите округ</option>";
        townSelect.innerHTML = "<option value='' selected>Выберите город</option>";
        counties.forEach((county) => {
            let option = document.createElement('option');
            option.value = county;
            option.innerText = county;
            countySelect.appendChild(option);
        })
    })
})

countySelect.addEventListener('change', (event) => {
    if (!event.target.value) {
        showSelects(countySelect, 'town-group');
        townSelect.innerHTML = "<option value='' selected>Выберите город</option>";
        return;
    }
    fetch(`${NGROK_URL}/api/countries/${countrySelect.value}/states/${stateSelect.value}/counties/${countySelect.value}/towns`, {
        headers: {
            Accept: "application/json"
        },
        method: "GET",
    }).then(response => response.json()).then(towns => {
        showSelects(countySelect, 'town-group');
        townSelect.innerHTML = "<option value='' selected>Выберите город</option>";
        towns.forEach((town) => {
            let option = document.createElement('option');
            option.value = town;
            option.innerText = town;
            townSelect.appendChild(option);
        })
    })
})

function showSelects(currentSelect, nextGroup) {
    currentSelect.value ? document.getElementsByClassName(`${nextGroup}`)[0].classList.remove(`${nextGroup}--hidden`) : document.getElementsByClassName(`${nextGroup}`)[0].classList.add(`${nextGroup}--hidden`)
}

// form to send filters

let form = document.getElementById('form');

form.addEventListener('submit', (e) => {
    e.preventDefault();
    document.getElementById('btn-submit').disabled = true;
    FORM_FIELDS_ERROR.forEach((elem) => {
        document.getElementById(elem) ? document.getElementById(elem).innerText = "" : null;
    })

    const formData = new FormData(e.currentTarget);

    fetch(`${NGROK_URL}/api/estates/filters`, {
        headers: {
            Accept: "application/json"
        },
        method: "POST",
        body: formData,

    }).then((response) => response.json())
        .then((json) => {
            document.getElementById('btn-submit').disabled = false;
            if (!json?.errors) {
                tg.close();
                return;
            }

            if (json?.errors) {
                for (let error in json?.errors) {
                    document.getElementById(`${error}-error`).innerText = json?.errors[error][0];
                }

                for (let i = 0; i < FORM_FIELDS_ERROR.length; i++) {
                    if (Object.keys(json?.errors).includes(FORM_FIELDS_ERROR[i].split('-')[0])) {
                        let scrollDiv = document.getElementById(`${FORM_FIELDS_ERROR[i]}`).closest('.form-group').offsetTop;
                        window.scrollTo({top: scrollDiv, behavior: 'smooth'});
                        break;
                    }
                }
            }
        })

})

let periods = document.getElementsByClassName('type_announcement__field');

function changeTypePrice(deal_type) {
    switch (deal_type) {
        case 'Продажа':
            document.getElementById('period-container').classList.add('d-none');
            for (let period of periods) {
                period.checked = false;
            }
            break;

        case 'Аренда':
            document.getElementById('period-container').classList.remove('d-none');
            break;
    }
}

document.getElementById('Продажа').addEventListener("change", () => {
    changeTypePrice('Продажа');
})
document.getElementById('Аренда').addEventListener("change", () => {
    changeTypePrice('Аренда');
})

// TODO: УДАЛИТЬ КОГДА НУЖНО БУДЕТ ДОБАВИТЬ ТИП УСЛУГИ ПРОДАЖА

document.getElementById("Аренда").checked = true;
changeTypePrice('Аренда');
document.getElementsByClassName("form-group")[0].classList.add('d-none');

let tg = window.Telegram.WebApp;
tg.expand();

document.getElementById('username').value = tg.initDataUnsafe.user.username;
document.getElementById('user_id').value = tg.initDataUnsafe.user.id;
document.getElementById('first_name').value = tg.initDataUnsafe.user.first_name;
document.getElementById('last_name').value = tg.initDataUnsafe.user.last_name;

let form = document.getElementById('form');

form.addEventListener('submit', (e) => {
    e.preventDefault();
    document.getElementById('btn-submit').disabled = true;
    FORM_FIELDS_ERROR.forEach((elem) => {
        document.getElementById(elem) ? document.getElementById(elem).innerText = "" : null;
    })

    const formData = new FormData(e.currentTarget);

    fetch(`${NGROK_URL}/estate/filters`, {
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
            }

            for (let error in json?.errors) {
                document.getElementById(`${error}-error`).innerText = json?.errors[error][0];
            }

            for (let i = 0; i < FORM_FIELDS_ERROR.length; i++) {
                if (Object.keys(json?.errors).includes(FORM_FIELDS_ERROR[i].split('-')[0])) {
                    let scrollDiv = document.getElementById(`${FORM_FIELDS_ERROR[i]}`).offsetTop;
                    window.scrollTo({top: scrollDiv - 110, behavior: 'smooth'});
                    break;
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


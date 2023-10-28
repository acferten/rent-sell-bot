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

    e.preventDefault();
    document.getElementById('btn-submit').disabled = true;

    const formData = new FormData(e.currentTarget);

    fetch(`https://13f0-77-106-104-230.ngrok-free.app/estate/filters`, {
        headers: {
            Accept: "application/json"
        },
        method: "POST",
        body: formData,

    })
        .then(() => tg.close())

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


let tg = window.Telegram.WebApp;
tg.expand();

document.getElementById('username').value = tg.initDataUnsafe.user.username;
document.getElementById('user_id').value = tg.initDataUnsafe.user.id;
document.getElementById('first_name').value = tg.initDataUnsafe.user.first_name;
document.getElementById('last_name').value = tg.initDataUnsafe.user.last_name;

tg.enableClosingConfirmation();

let form = document.getElementById('form');

form.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = Object.fromEntries(new FormData(e.target).entries());

    fetch("https://a48a-79-136-237-88.ngrok-free.app/estate", {
        method: "POST",
        body: JSON.stringify({
            initData: tg.initData,
            ...formData
        }),
        headers: {
            "Content-type": "application/json",
            "Accept": "application/json"
        }
    })
        .then((response) => response.json())
        .then((json) => console.log(json));
})



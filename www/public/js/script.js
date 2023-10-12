let tg = window.Telegram.WebApp;
tg.expand();

document.getElementById('username').value = tg.initDataUnsafe.user.username;
document.getElementById('user_id').value = tg.initDataUnsafe.user.id;
document.getElementById('first_name').value = tg.initDataUnsafe.user.first_name;
document.getElementById('last_name').value = tg.initDataUnsafe.user.last_name;

tg.enableClosingConfirmation();

tg.MainButton.show();

function validateFormOnSubmit(value) {
    return value
}

let form = document.getElementById('form');

form.addEventListener('submit', () => {
    fetch("https://098d-176-65-60-218.ngrok-free.app/api/webappdata", {
        method: "POST",
        body: JSON.stringify({
            initData: tg.initData,

        }),
        headers: {
            "Content-type": "application/json; charset=UTF-8"
        }
    })
        .then((response) => response.json())
        .then((json) => console.log(json))
})

// }


// tg.onEvent('mainButtonClicked', () => {
//     tg.MainButton.showProgress();
//     fetch("https://098d-176-65-60-218.ngrok-free.app/api/webappdata", {
//         method: "POST",
//         body: JSON.stringify({
//             initData: tg.initData,
//
//         }),
//         headers: {
//             "Content-type": "application/json; charset=UTF-8"
//         }
//     })
//         .then((response) => response.json())
//         .then((json) => console.log(json))
// })




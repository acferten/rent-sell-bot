let loginInput = document.getElementById('username');
let passwordInput = document.getElementById('password');

let clearErrors = (error) => {
    error.innerText = "";
}

loginInput.addEventListener('input', (event) => {
    let error = document.getElementsByClassName('login-error')[0];
    if (error) {
        clearErrors(error);
    }
})

passwordInput.addEventListener('input', (event) => {
    let error = document.getElementsByClassName('password-error')[0];
    if (error) {
        clearErrors(error);
    }
})



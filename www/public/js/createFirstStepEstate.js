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

    const formData = new FormData(e.currentTarget);

    fetch(`https://ccc4-5-136-99-97.ngrok-free.app/estate/`, {
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
                document.getElementById(`${error}-error`).innerText = json.errors[error][0];
            }
        })
})

function changeTypePrice(deal_type) {
    switch (deal_type) {
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

const photoInput = document.getElementById('file-input');
const photoContainer = document.getElementById('preview-container');

photoInput.addEventListener('change', handleFileUpload);

function handleFileUpload(event) {
    const files = event.target.files;

    const selectedPhotos = Array.from(files);

    selectedPhotos.forEach((photoFile) => {
        const reader = new FileReader();
        reader.onload = () => {
            createPhotoElement(reader.result, photoFile);
        };
        reader.readAsDataURL(photoFile);
    });
}

function createPhotoElement(photoDataUrl, photoFile) {
    const photoElement = document.createElement('div');
    photoElement.classList.add('preview-container__photo');
    photoElement.style.backgroundImage = `url('${photoDataUrl}')`;

    const deleteButton = document.createElement('span');
    deleteButton.innerText = 'x';
    deleteButton.classList.add('delete');
    deleteButton.addEventListener('click', () => {
        deletePhoto(photoElement, photoFile);
    });
    photoElement.appendChild(deleteButton);

    photoContainer.prepend(photoElement);
}

function deletePhoto(photoElement, photoFile) {
    photoElement.remove();
}


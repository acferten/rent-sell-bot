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
    FORM_FIELDS_ERROR.forEach((elem) => {
        document.getElementById(elem).innerText = "";
    })

    document.getElementById('btn-submit').disabled = true;

    const formData = new FormData(e.currentTarget);

    fetch(`${NGROK_URL}/estate/`, {
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

const photosInput = document.getElementById('photos');
const mainPhotoInput = document.getElementById('main_photo');
const photosContainer = document.getElementById('photos-container');
const mainPhotoContainer = document.getElementById('main-photo-container');
photosInput.addEventListener('change', (event) => {
    handleFileUpload(event, photosContainer, 10);
    removeAddButton(event.target, 10, mainPhotoContainer);
});

mainPhotoInput.addEventListener('change', (event) => {
    handleFileUpload(event, mainPhotoContainer, 1);
    removeAddButton(event.target, 1, mainPhotoContainer);
});

function removeAddButton(input, maxElems, container) {
    let isMax = input.files.length >= maxElems;
    if (isMax) container.lastChild.remove();
    if (input.files.length === 0) container.innerHTML = `<label for="${input.getAttribute('id')}" class="photo-uploader__add-button">+</label>`
}

function handleFileUpload(event, photoContainer, maxElemsInContainer) {
    const files = event.target.files;
    photoContainer.innerHTML = `<label for="${event.target.getAttribute('id')}" class="photo-uploader__add-button">+</label>`
    const selectedPhotos = Array.from(files);

    selectedPhotos.forEach((photoFile) => {
        const reader = new FileReader();
        reader.onload = () => {
            createPhotoElement(reader.result, photoFile, photoContainer, event.target, maxElemsInContainer);
        };
        reader.readAsDataURL(photoFile);
    });
}

function createPhotoElement(photoDataUrl, photoFile, photoContainer, photoInput, maxElems) {
    const photoElement = document.createElement('div');
    photoElement.classList.add('preview-container__photo');
    photoElement.style.backgroundImage = `url('${photoDataUrl}')`;

    const deleteButton = document.createElement('span');
    deleteButton.innerText = 'x';
    deleteButton.classList.add('delete');
    deleteButton.addEventListener('click', () => {
        deletePhoto(photoElement, photoFile, photoContainer, photoInput, maxElems);
    });
    photoElement.appendChild(deleteButton);

    photoContainer.prepend(photoElement);
}

function deletePhoto(photoElement, photoFile, photoContainer, photoInput, maxElems) {
    const currentIndex = Array.from(photoContainer.children).indexOf(photoElement);
    photoElement.remove();

    const dt = new DataTransfer()
    const {files} = photoInput

    for (let i = 0; i < files.length; i++) {
        const file = files[i]
        if (currentIndex !== i)
            dt.items.add(file)
    }

    photoInput.files = dt.files
    removeAddButton(photoInput, maxElems, photoContainer);
}


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

            let scrollDiv = document.getElementById(`${Object.keys(json?.errors)[0]}-error`).offsetTop;
            window.scrollTo({ top: scrollDiv-70, behavior: 'smooth'});
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
const photosInputHidden = document.getElementById('photos-hidden');
const photosContainer = document.getElementById('photos-container');

const mainPhotoInput = document.getElementById('main-photo');
const mainInputHidden = document.getElementById('main-photo-hidden');
const mainPhotoContainer = document.getElementById('main-photo-container');


photosInputHidden.addEventListener('change', (event) => {
    addInOtherInputFiles(event.target, photosInput);
    handleFileUpload(photosInput, photosContainer, 10);
    removeAddButton(photosInput, 10, photosContainer);
})

mainInputHidden.addEventListener('change', (event) => {
    addInOtherInputFiles(event.target, mainPhotoInput);
    handleFileUpload(mainPhotoInput, mainPhotoContainer, 1);
    removeAddButton(mainPhotoInput, 1, mainPhotoContainer);
})

function addInOtherInputFiles(fromInput, toInput) {
    const dt = new DataTransfer()

    for (let i = 0; i < fromInput.files.length; i++) {
        const file = fromInput.files[i]
        dt.items.add(file)
    }

    for (let i = 0; i < toInput.files.length; i++) {
        const file = toInput.files[i]
        dt.items.add(file)
    }

    toInput.files = dt.files;
}

function handleFileUpload(photoInput, photoContainer, maxElem) {
    photoContainer.innerHTML = `<label for="${photoInput.getAttribute('id')}-hidden" class="photo-uploader__add-button">+</label>`;
    for (let i = photoInput.files.length - 1; i >= 0; i--) {
        const reader = new FileReader();
        reader.onload = () => {
            createPhotoElement(reader.result, photoInput.files[i], photoContainer, photoInput, maxElem);
        };
        reader.readAsDataURL(photoInput.files[i]);
    }
}

function removeAddButton(input, maxElems, container) {
    let isMax = input.files.length >= maxElems;

    if (isMax && container.lastChild?.tagName === 'LABEL') {
        container.lastChild.remove();
    } else if (
        (input.files.length < maxElems && container.lastChild?.tagName !== 'LABEL')
    ) {
        const label = document.createElement('label');
        label.setAttribute('for', `${input.getAttribute('id')}-hidden`);
        label.classList.add('photo-uploader__add-button');
        label.innerText = '+';
        container.appendChild(label);
    }
}


function createPhotoElement(photoDataUrl, photoFile, photoContainer, photoInput, maxElem) {
    const photoElement = document.createElement('div');
    photoElement.classList.add('preview-container__photo');
    photoElement.style.backgroundImage = `url('${photoDataUrl}')`;

    const deleteButton = document.createElement('span');
    deleteButton.innerText = 'x';
    deleteButton.classList.add('delete');
    deleteButton.addEventListener('click', () => {
        deletePhoto(photoElement, photoFile, photoContainer, photoInput, maxElem);
    });
    photoElement.appendChild(deleteButton);

    photoContainer.prepend(photoElement);
}

function deletePhoto(photoElement, photoFile, photoContainer, photoInput, maxElem) {
    const currentIndex = Array.from(photoContainer.children).indexOf(photoElement);
    photoElement.remove();

    const dt = new DataTransfer()
    const {files} = photoInput

    for (let i = 0; i < files.length; i++) {
        const file = files[i]
        if (currentIndex !== i)
            dt.items.add(file)
    }

    photoInput.files = dt.files;
    removeAddButton(photoInput, maxElem, photoContainer);
}

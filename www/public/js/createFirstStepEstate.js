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
        document.getElementById(elem) ? document.getElementById(elem).innerText = "" : null;
    })

    document.getElementById('btn-submit').disabled = true;
    document.getElementById('btn-submit').innerText = "Обработка...";

    const formData = new FormData(e.currentTarget);
    let monthPrice = formData.get('month_price');
    let yearPrice = formData.get('year_price');
    let formatPeriods = [];
    let rentPeriods = formData.getAll('periods[]');
    rentPeriods.forEach((period) => {
        if (period === "Месяц") {
            formatPeriods.push({period: period, price: monthPrice})
        } else {
            formatPeriods.push({period: period, price: yearPrice})
        }
    })
    formatPeriods.forEach((period) => {
        formData.append('periods[]', JSON.stringify(period))
    })

    console.log(formData);

    fetch(`${NGROK_URL}/estate`, {
        headers: {
            Accept: "application/json"
        },
        method: "POST",
        body: formData,

    })
        .then((response) => response.json())
        .then((json) => {
            document.getElementById('btn-submit').disabled = false;
            document.getElementById('btn-submit').innerText = "Сохранить";

            for (let error in json?.errors) {
                document.getElementById(`${error}-error`).innerText = json.errors[error][0];
            }


            if (json?.errors) {
                for (let i = 0; i < FORM_FIELDS_ERROR.length; i++) {
                    if (Object.keys(json?.errors).includes(FORM_FIELDS_ERROR[i].split('-')[0])) {
                        let scrollDiv = document.getElementById(`${FORM_FIELDS_ERROR[i]}`).offsetTop;
                        window.scrollTo({top: scrollDiv - 110, behavior: 'smooth'});
                        break;
                    }
                }
            }
        })
})

function changeTypePrice(deal_type) {
    switch (deal_type) {
        case 'Продажа':
            document.getElementById('price-container').classList.remove('d-none');
            document.getElementById('period-container').classList.add('d-none');
            document.getElementById('month_price-container').classList.add('d-none');
            document.getElementById('year_price-container').classList.add('d-none');
            document.getElementById('Месяц').checked = false;
            document.getElementById('Год').checked = false;
            document.getElementById('month_price').value = null;
            document.getElementById('year_price').value = null;
            break;

        case 'Аренда':
            document.getElementById('price-container').classList.add('d-none');
            document.getElementById('period-container').classList.remove('d-none');
            document.getElementById('price').value = null;
            break;
    }
}

document.getElementById('Продажа').addEventListener("change", () => {
    changeTypePrice('Продажа');
})
document.getElementById('Аренда').addEventListener("change", () => {
    changeTypePrice('Аренда');
})

document.getElementById('Месяц').addEventListener("change", () => {
    console.log("изменен");
    document.getElementById('month_price-container').classList.toggle('d-none');
})

document.getElementById('Год').addEventListener("change", () => {
    document.getElementById('year_price-container').classList.toggle('d-none');
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

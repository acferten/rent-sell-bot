let tg = window.Telegram.WebApp;
tg.expand();

const ID_ESTATE = window.location.href.match(/\/estate\/(\d+)/)[1];
document.getElementById('username').value = tg.initDataUnsafe.user.username;
document.getElementById('user_id').value = tg.initDataUnsafe.user.id;
document.getElementById('first_name').value = tg.initDataUnsafe.user.first_name;
document.getElementById('initData').value = tg.initData;
document.getElementById('last_name').value = tg.initDataUnsafe.user.last_name;

let form = document.getElementById('form');

form.addEventListener('submit', (e) => {
    FORM_FIELDS_ERROR.forEach((elem) => {
        document.getElementById(elem) ? document.getElementById(elem).innerText = "" : null;
    })

    document.getElementById('btn-submit').disabled = true;
    document.getElementById('btn-submit').innerText = "Обработка...";

    e.preventDefault();
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
    formData.set('periods', JSON.stringify(formatPeriods));

    fetch(`${NGROK_URL}/api/estates/${ID_ESTATE}?_method=PATCH`, {
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
            if (!json?.errors) {
                tg.close();
            }

            for (let error in json?.errors) {
                document.getElementById(`${error}-error`).innerText = json.errors[error][0];
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

function changeTypePrice(deal_type) {
    switch (deal_type) {
        case 'Продажа':
            document.getElementById('price-container').classList.remove('d-none');
            document.getElementById('period-container').classList.add('d-none');
            document.getElementById('month_price-container').classList.add('d-none');
            document.getElementById('year_price-container').classList.add('d-none');
            document.getElementById('month').checked = false;
            document.getElementById('year').checked = false;
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

document.getElementById('month').addEventListener("change", () => {
    document.getElementById('month_price-container').classList.toggle('d-none');
    document.getElementById('month_price').value = null;
})

document.getElementById('year').addEventListener("change", () => {
    document.getElementById('year_price-container').classList.toggle('d-none');
    document.getElementById('year_price').value = null;
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

// PUT PHOTOS FROM BACKEND IN INPUT

const regex = /background-image: url\('([^']+)'\);/;
let imagesFromBackend = [];


for (let i = 0; i < photosContainer.children.length - 1; i++) {
    const match = photosContainer.children[i].getAttribute('style').match(regex);
    imagesFromBackend.push(`${NGROK_URL}${match[1]}`);
}

const dt1 = new DataTransfer()

async function getBlob() {
    const fetchPromises = imagesFromBackend.map(url =>
        fetch(url)
            .then(response => response.blob())
            .then(blob => new File([blob], url.split('/').pop()))
    );

    const files = await Promise.all(fetchPromises);

    files.forEach(file => {
        dt1.items.add(file);
    });
}

getBlob().then(() => {
    photosContainer.innerHTML = `<label for="${photosInput.getAttribute('id')}-hidden" class="photo-uploader__add-button">+</label>`
    photosInput.files = dt1.files;
    Array.from(photosInput.files).forEach((photoFile) => {
        const reader = new FileReader();
        reader.onload = () => {
            createPhotoElement(reader.result, photoFile, photosContainer, photosInput);
        };
        reader.readAsDataURL(photoFile);
    })
    removeAddButton(photosInput, 10, photosContainer);
});

const dt2 = new DataTransfer()

const mainPhoto = async () => {
    let url = mainPhotoContainer.children[0].getAttribute('style').match(regex)[1];
    const photoPromise = fetch(url)
        .then(response => response.blob())
        .then(blob => new File([blob], url.split('/').pop()));

    let photo = await photoPromise;
    dt2.items.add(photo)
}

mainPhoto().then(() => {
    mainPhotoContainer.innerHTML = `<label for="${mainPhotoInput.getAttribute('id')}-hidden" class="photo-uploader__add-button">+</label>`
    mainPhotoInput.files = dt2.files;
    const reader = new FileReader();
    reader.onload = () => {
        createPhotoElement(reader.result, mainPhotoInput.files[0], mainPhotoContainer, mainPhotoInput, 1);
    };
    reader.readAsDataURL(mainPhotoInput.files[0]);
    removeAddButton(mainPhotoInput, 1, mainPhotoContainer);
})

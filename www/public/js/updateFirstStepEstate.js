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
        document.getElementById(elem).innerText = "";
    })

    document.getElementById('btn-submit').disabled = true;

    e.preventDefault();
    const formData = new FormData(e.currentTarget);

    fetch(`${NGROK_URL}/estate/${ID_ESTATE}?_method=PATCH`, {
        headers: {
            Accept: "application/json"
        },
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((json) => {
            document.getElementById('btn-submit').disabled = false;
            if (!json?.errors) {
                tg.close();
            }

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

if (document.getElementById('Продажа').checked) {
    changeTypePrice('Продажа');
} else if (document.getElementById('Аренда').checked) {
    changeTypePrice('Аренда');
}

document.getElementById('Продажа').addEventListener("change", () => {
    changeTypePrice('Продажа');
})
document.getElementById('Аренда').addEventListener("change", () => {
    changeTypePrice('Аренда');
})

const photosInput = document.getElementById('photos');
const mainPhotoInput = document.getElementById('main_photo');
const photosHidden = document.getElementById('photos-hidden');
const photosContainer = document.getElementById('photos-container');
const mainPhotoContainer = document.getElementById('main-photo-container');

photosHidden.addEventListener('change', () => {
    handleFileUpload(photosInput, photosContainer)
    removeAddButton(photosInput, 10, photosContainer)
});

photosInput.addEventListener('change', (event) => {
    handleFileUpload(event, photosContainer)
    removeAddButton(event.target, 10, photosContainer)
});

mainPhotoInput.addEventListener('change', (event) => {
    handleFileUpload(event, mainPhotoContainer);
    removeAddButton(event.target, 1, mainPhotoContainer)
});
function removeAddButton(input, maxElems, container) {
    let isMax = input.files.length >= maxElems;
    if (isMax) container.lastChild.remove();
    if (input.files.length === 0) container.innerHTML = `<label for="${input.getAttribute('id')}" class="photo-uploader__add-button">+</label>`
}


function handleFileUpload(event, photoContainer) {
    const files = event.target.files;
    photoContainer.innerHTML = `<label for="${event.target.getAttribute('id')}" class="photo-uploader__add-button">+</label>`
    const selectedPhotos = Array.from(files);

    selectedPhotos.forEach((photoFile) => {
        const reader = new FileReader();
        reader.onload = () => {
            createPhotoElement(reader.result, photoFile, photoContainer, event.target);
        };
        reader.readAsDataURL(photoFile);
    });
}

function createPhotoElement(photoDataUrl, photoFile, photoContainer, photoInput, maxElems = 10) {
    removeAddButton(photoInput, maxElems, photoContainer)
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
    photosContainer.innerHTML = `<label for="${photosInput.getAttribute('id')}" class="photo-uploader__add-button">+</label>`
    photosInput.files = dt1.files;
    Array.from(photosInput.files).forEach((photoFile) => {
        const reader = new FileReader();
        reader.onload = () => {
            createPhotoElement(reader.result, photoFile, photosContainer, photosInput);
        };
        reader.readAsDataURL(photoFile);
    })
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
    mainPhotoContainer.innerHTML = `<label for="${mainPhotoInput.getAttribute('id')}" class="photo-uploader__add-button">+</label>`
    mainPhotoInput.files = dt2.files;
    const reader = new FileReader();
    reader.onload = () => {
        createPhotoElement(reader.result, mainPhotoInput.files[0], mainPhotoContainer, mainPhotoInput, 1);
    };
    reader.readAsDataURL(mainPhotoInput.files[0]);
})

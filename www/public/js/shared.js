export function setErrorTextField(errorTextField, error) {
    document.getElementById(errorTextField).innerText = error;
}

export function setDataValue(elementId, data) {
    document.getElementById(elementId).value = data;
}

sendReportShowButton = document.getElementById('btn-report');
sendReportForm = document.getElementById('report-form');
sendReportFormButton = document.getElementById('send-report');
sendReportFormWrapper = document.getElementsByClassName('report-form-wrapper')[0];
const ID_ESTATE = window.location.href.match(/\/estate\/(\d+)/)[1];
sendReportShowButton.addEventListener('click', (event) => {
    sendReportFormWrapper.classList.toggle('d-none');
    sendReportShowButton.classList.toggle('placeholder-wave');
    window.scrollTo({top: 9999, behavior: 'instant'});
});

sendReportForm.addEventListener('submit', (event) => {
    event.preventDefault();
    sendReportFormButton.disabled = true;
    sendReportFormButton.innerText = "💆‍♂️ Релакс… Идёт загрузка объявления";
    const formData = new FormData(event.currentTarget);
    fetch(`${NGROK_URL}/api/estates/${ID_ESTATE}/report`, {
        headers: {
            Accept: "application/json"
        },
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((json) => {
            sendReportFormButton.disabled = false;
            sendReportFormButton.innerText = "Отправить жалобу";

            if (json.error) {
                document.getElementById(`report-error`).innerText = json.errors[error][0];
            } else {
                sendReportShowButton.click();
                alert("Жалоба успешно отправлена");
            }
        });
})

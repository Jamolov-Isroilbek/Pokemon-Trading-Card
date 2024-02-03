function clearForm(formId) {
    let form = document.getElementById(formId);
    let inputs = form.querySelectorAll(
        'input[type="text"], input[type="number"], input[type="email"], input[type="password"], textarea'
    );
    for (let i = 0; i < inputs.length; i++) {
        inputs[i].value = "";
    }
}

function showAlert(message) {
    console.log('Show alert: ' + message);
    let alertBox = document.getElementById('alert-box');
    let alertMessage = document.getElementById('alert-message');

    alertMessage.textContent = message;
    alertBox.classList.remove('hidden');
}

function closeAlert() {
    console.log('Close alert');
    let alertBox = document.getElementById('alert-box');
    alertBox.classList.add('hidden');
}

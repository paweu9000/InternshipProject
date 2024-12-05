
function showError(data) {
    errors = data.split('\n');
    errorCards = '';
    errors.forEach(error => {
        errorCards += createErrorCard(error);
    });
    document.getElementById('errors').innerHTML = errorCards;
}

function createErrorCard(error) {
    return `
        <div class="uk-card uk-card-danger" style='background-color: red'>
            <p class="uk-margin">
                ${error}
            </p>
        </div>
    `
}
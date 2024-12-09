
function showError(data) {
    errors = data.split('\n');
    errorCards = '';
    errors.forEach(error => {
        errorCards += createErrorCard(error);
    });
    document.getElementById('errors').innerHTML = errorCards;
}

function clearErrors() {
    document.getElementById('errors').innerHTML = '';
} 

function createErrorCard(error) {
    return `
        <div class="card text-bg-warning mb-3 row justify-content-center">
            <div class="card-header ">
                Error
            </div>
            <div class="card-body">
                <p class="card-text">
                    ${error}
                </p>
            </div>
        </div>
    `
}
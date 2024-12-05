
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
        <div class="card text-bg-warning mb-3" style='max-width: 22rem;'>
            <div class="card-header">
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
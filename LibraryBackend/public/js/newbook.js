document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('book-form');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        const bookDto = {
            title: document.getElementById('book-title').value,
            author:  document.getElementById('book-author').value,
            isbn: document.getElementById('book-isbn').value,
            yearOfPublication: parseInt(document.getElementById('book-yearOfPublication').value)
        };

        fetch(data, {
            method: "POST",
            body: JSON.stringify(bookDto),
            headers: {
                'Authorization': `Bearer ${jwt}`,
                "Content-type": "application/json; charset=UTF-8"
            }
        }).then(response => {
            return response.json();
        })
        .then(data => {
            if (data.error) {
                showError(data.error);
            } else {
                const redirect = successURL.replace('/0', '/'+ data.id.toString());
                window.location.replace(redirect);
            }
        })
        .catch(error => {
            showError(error);
        })
    });
});
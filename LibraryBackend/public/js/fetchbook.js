let book = null;

function generateBookCard(book) {
    return `
    <div class="card" style="width: 30rem; margin: 1%;">
        <div class="card-body">
            <p class="card-text">ID: ${book.id} </p>
            <p class="card-text">Title: ${book.title} </p>
            <p class="card-text">Author: ${book.author} </p>
            <p class="card-text">Isbn: ${book.isbn} </p>
            <p class="card-text">Year of publication: ${book.yearOfPublication} </p>
        </div>
        <button id="book-edit" class="btn btn-dark">Edit</button>
        <button id="book-delete" class="btn btn-danger">Delete</button>
    </div>
    `
}

function generateEditForm(book) {
    return `
    <div class="d-flex justify-content-center align-items-center mt-1">
    <form id="book-form" class="row justify-content-center">
        <div class="mb-3">
            <label class="form-label" >Title</label>
            <div class="uk-form-controls">
            <input
                class="form-control"
                id="book-title"
                minlength="3"
                maxlength="255"
                type="text"
                placeholder="Title"
                required
                value="${book.title}"
            />
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" >Author</label>
            <div class="uk-form-controls">
            <input
                class="form-control"
                id="book-author"
                minlength="3"
                maxlength="255"
                type="text"
                placeholder="Author"
                value="${book.author}"
                required
            />
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">ISBN</label>
            <div class="uk-form-controls">
            <input
                class="form-control"
                minlength="10"
                maxlength="13"
                id="book-isbn"
                type="text"
                placeholder="ISBN"
                value="${book.isbn}"
                required
            />
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" >Year of publication</label>
            <div class="uk-form-controls">
            <input
                class="form-control"
                min="1900"
                id="book-yearOfPublication"
                type="number"
                placeholder="i.e. 1987"
                value="${book.yearOfPublication}"
                required
            />
            </div>
        </div>
        <button class="btn btn-primary" type="submit">Edit</button>
    </form>
</div>
<div class="d-flex justify-content-center align-items-center mt-1">
    <button id="edit-cancel" class="btn btn-danger row justify-content-center">Cancel</button>
</div>
    `
}

function attachEventListeners() {
    const deleteButton = document.getElementById('book-delete');
    const editButton = document.getElementById('book-edit');

     deleteButton.addEventListener('click', function() {
        fetch(delete_endpoint, {
            method: "DELETE",
            headers: {
                'Authorization': `Bearer ${jwt}`,
                "Content-type": "application/json; charset=UTF-8"
            }
        }).then(response => {
            if (response.status === 204) {
                window.location.replace(delete_redirect);
            }
        })
        .catch(error => {
            console.log(error);
        })
    });

    editButton.addEventListener('click', function() {
        document.getElementById('book').innerHTML = generateEditForm(book);

        const cancelButton = document.getElementById("edit-cancel");

        cancelButton.addEventListener('click', function() {
            clearErrors();
            document.getElementById('book').innerHTML = generateBookCard(book);
            attachEventListeners();
        });

        const form = document.getElementById('book-form');

        form.addEventListener('submit', function(event) {
            event.preventDefault();
    
            const bookDto = {
                title: document.getElementById('book-title').value,
                author:  document.getElementById('book-author').value,
                isbn: document.getElementById('book-isbn').value,
                yearOfPublication: parseInt(document.getElementById('book-yearOfPublication').value)
            };
            
            fetch(edit_endpoint, {
                method: "PUT",
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
                    clearErrors();
                    book = data;
                    document.getElementById('book').innerHTML = generateBookCard(book);
                    attachEventListeners();
                }
            })
            .catch(error => {
                showError(error);
            })
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const url = data;

    fetch(url, {
        headers: {
            'Authorization': `Bearer ${jwt}`,
            'Content-Type': 'application/json',
        }
    }).then(response => {
        if (response.status === 400) {
            window.location.replace(delete_redirect);
        }
        return response.json();
    })
    .then(data => {
        bookCard = generateBookCard(data);
        document.getElementById('book').innerHTML = bookCard;
        book = data;

        attachEventListeners();
    })
    .catch(error => {
        console.log('There was an error: ', error);
    });
});
function generateBookCard(book) {
    return `
    <div class="card" style="width:30rem;>
        <div class="card-body">
            <button class="btn btn-dark">Edit</button>
            <button class="btn btn-danger">Delete</button>
            <p class="card-text">ID: ${book.id} </p>
            <p class="card-text">Title: ${book.title} </p>
            <p class="card-text">Author: ${book.author} </p>
            <p class="card-text">Isbn: ${book.isbn} </p>
            <p class="card-text">Year of publication: ${book.yearOfPublication} </p>
        </div>
    </div>
    `
}


document.addEventListener('DOMContentLoaded', function() {
    const url = data;

    fetch(url).then(response => {
        return response.json();
    })
    .then(data => {
        bookCard = generateBookCard(data);
        document.getElementById('book').innerHTML = bookCard;
    })
    .catch(error => {
        console.log('There was an error: ', error);
    });
});
function bookCard(book) {
    return ` <div id="book-${book.id}" class="uk-card uk-card-body card-secondary uk-align-center" style="max-width: 20%; margin: 1%">
            <h3 class="uk-card-title">Title: ${book.title}</h3>
            <p class="uk-margin">Author: ${book.author}</p>
            <p class="uk-margin">ISBN: ${book.isbn}</p>
            <p class="uk-margin">Year of publication: ${book.yearOfPublication}</p>
        </div>
        `
}


document.addEventListener('DOMContentLoaded', function() {
    const url = data;

    fetch(url).then(response => {
        if (!response.ok) {
            throw new Error("Response not ok");
        }
        return response.json();
    })
    .then(data => {
        rowsToAppend = '';
        data.forEach(book => {
            rowsToAppend += bookCard(book);
        });
        
        document.getElementById('books').innerHTML = rowsToAppend;
    })
    .catch(error => {
        console.log('There was an error: ', error);
    });
});
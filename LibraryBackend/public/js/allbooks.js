function bookCard(book) {
    const dynamicUrl = singleBookEndpoint.replace('/0', book.id);

    return ` 
                <div id="book-${book.id}" class="card" style="width: 30rem; margin: 1%;">
                    <div class="card-body">
                        <h3 class="card-title">Title: ${book.title}</h3>
                        <p class="card-text">Author: ${book.author}</p>
                        <p class="card-text">ISBN: ${book.isbn}</p>
                        <p class="card-text">Year of publication: ${book.yearOfPublication}</p>
                    
                        <a href="${dynamicUrl}">
                            <button class="btn btn-primary">
                                View
                            </button>
                        </a>
                    </div>
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
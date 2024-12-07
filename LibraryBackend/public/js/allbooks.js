let books = [];

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

function sortBy(selectedValue) {
    if (selectedValue === 'Default') {
        return books;
    } else if (selectedValue === 'author') {
        let authorBooks = [...books];

        authorBooks.sort((a, b) => {
            if (a.author.toLowerCase() < b.author.toLowerCase()) return -1;
            if (a.author.toLowerCase() > b.author.toLowerCase()) return 1;
            return 0;
        })
        
        return authorBooks;
    } else if (selectedValue === 'title') {
        let titleBooks = [...books];

        titleBooks.sort((a, b) => {
            if (a.title.toLowerCase() < b.title.toLowerCase()) return -1;
            if (a.title.toLowerCase() > b.title.toLowerCase()) return 1;
            return 0;
        })
        
        return titleBooks;
    } else if (selectedValue === 'yearOfPublication') {
        let yearBooks = [...books];

        yearBooks.sort((a, b) => {
            if (a.yearOfPublication <= b.yearOfPublication) return -1;
            if (a.yearOfPublication > b.title.yearOfPublication) return 1;
        })
        
        return yearBooks;
    }
}

function applyQuery(book, field, query) {
    if (field === 'author') {
        return book.author.toLowerCase().includes(query.toLowerCase());
    } else if (field === 'title') {
        return book.title.toLowerCase().includes(query.toLowerCase());
    } else if (field === 'yearOfPublication') {
        return book.yearOfPublication == parseInt(query);
    }
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
        books = data;
    })
    .catch(error => {
        console.log('There was an error: ', error);
    });

    const sortSelect = document.getElementById('sort-book');
    const searchBy = document.getElementById('search-field');
    const searchInput = document.getElementById('search-input');

    sortSelect.addEventListener('change', function() {
        const selectedValue = this.value;

        let rowsToAppend = '';

        let sortedBooks = sortBy(selectedValue);

        if (searchInput.value !== '') {
            sortedBooks = sortedBooks.filter((book) => {
                return applyQuery(book, searchBy.value, searchInput.value);
            })
        }

        sortedBooks.forEach(book => {
            rowsToAppend += bookCard(book);
        });
        
        document.getElementById('books').innerHTML = rowsToAppend;
    });

    searchBy.addEventListener('change', function() {
        searchInput.value = '';
    });

    searchInput.addEventListener('input', function() {
        const applySort = sortSelect.value;
        sortedBooks = sortBy(applySort);

        const searchByValue = searchBy.value;
        const query = searchInput.value;

        const filtered = sortedBooks.filter((book) => {
            return applyQuery(book, searchByValue, query);
        });
        rowsToAppend = '';
        
        filtered.forEach(book => {
            rowsToAppend += bookCard(book);
        })

        document.getElementById('books').innerHTML = rowsToAppend;
    });
});
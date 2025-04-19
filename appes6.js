// Base Book class
class Book {
    constructor(title, author, isbn) {
        this.title = title;
        this.author = author;
        this.isbn = isbn;
    }

    getBookType() {
        return "Physical Book";
    }
}

// Inheritance: EBook extends Book
class EBook extends Book {
    constructor(title, author, isbn, fileSize) {
        super(title, author, isbn);
        this.fileSize = fileSize;
    }

    getBookType() {
        return "E-Book";
    }
}

// Inheritance: AudioBook extends Book
class AudioBook extends Book {
    constructor(title, author, isbn, duration) {
        super(title, author, isbn);
        this.duration = duration;
    }

    getBookType() {
        return "Audio Book";
    }
}

class UI {
    constructor(){
        this.uibtitle = "";
        this.uibauthor = "";
        this.uibisbn = "";
    }

    addBookToList(book) {
        const list = document.getElementById('book-list');

        const row = document.createElement('tr');
        row.innerHTML = `
        <td class="mtitle">${book.title}</td>
        <td class="mauthor">${book.author}</td>
        <td class="misbn">${book.isbn}</td>
        <td class="mtype">${typeof book.getBookType === 'function' ? book.getBookType() : 'Unknown'}</td>
        <td>
        <a href="#" class="delete">X</a> 
        <button class="edit">Edit</button> 
        </td>`;

        list.appendChild(row);
    }

    showAlert(message, className) {
        const div = document.createElement('div');
        div.className = `alert ${className}`;
        div.appendChild(document.createTextNode(message));
        const container = document.querySelector('.container');
        const formDiv = document.querySelector('.addformdiv').style.display == 'block' ? document.querySelector('.addformdiv') : document.querySelector('.modal');
        container.insertBefore(div, formDiv);
        setTimeout(() => {
            document.querySelector('.alert').remove();
        }, 3000);
    }

    deleteBook(target) {
        if (target.className === 'delete') {
            target.parentElement.parentElement.remove();
        }
    }

    editBook(target) {
        if (target.className === 'edit') {
            let liEl = target.parentElement.parentElement;
            this.uibtitle = liEl.querySelector('.mtitle').textContent;
            this.uibauthor = liEl.querySelector('.mauthor').textContent;
            this.uibisbn = liEl.querySelector('.misbn').textContent;
            this.showModal(this.uibtitle, this.uibauthor, this.uibisbn);
        }
    }

    clearFields() {
        document.getElementById('title').value = '';
        document.getElementById('author').value = '';
        document.getElementById('isbn').value = '';
    }

    showModal(bt, ba, bi){
        const addFormDiv = document.querySelector('.addformdiv');
        addFormDiv.style.display = "none";

        let modal = document.querySelector('.modal')

        modal.style.display = "block";

        const mform = document.querySelector('#mform');
        mform.mtitle.value = bt;
        mform.mauthor.value = ba;
        mform.misbn.value = bi;
    }

    closeModal(){
        const addFormDiv = document.querySelector('.addformdiv');
        addFormDiv.style.display = "block";

        let modal = document.querySelector('.modal')

        modal.style.display = "none";
    }

    emptyBookList(){
        const booklist = document.querySelector('#book-list');
        booklist.innerHTML = '';
    }
}

class Store {
    static getBooks() {
        let books;
        if(localStorage.getItem('books') === null) {
            books = [];
        } else {
            books = JSON.parse(localStorage.getItem('books'));
        }
        return books;
    }

    static displayBooks() {
        const books = Store.getBooks();
        const ui = new UI();
        ui.emptyBookList();
        books.forEach(function(book) {
            let bookObj;
            if (book.type === "E-Book") {
                bookObj = new EBook(book.title, book.author, book.isbn, book.fileSize);
            } else if (book.type === "Audio Book") {
                bookObj = new AudioBook(book.title, book.author, book.isbn, book.duration);
            } else {
                bookObj = new Book(book.title, book.author, book.isbn);
            }
            ui.addBookToList(bookObj);
        });
    }

    static addBook(book) {
        const books = Store.getBooks();
        const bookData = {
            title: book.title,
            author: book.author,
            isbn: book.isbn,
            type: book.getBookType()
        };

        // Tambahkan field tambahan berdasarkan jenis buku
        if (book instanceof EBook) {
            bookData.fileSize = book.fileSize;
        } else if (book instanceof AudioBook) {
            bookData.duration = book.duration;
        }

        books.push(bookData);
        localStorage.setItem('books', JSON.stringify(books));
    }

    static removeBook(isbn) {
        const books = Store.getBooks();
        books.forEach(function(book, index){
            if(book.isbn === isbn){
                books.splice(index,1);
            }
        });
        localStorage.setItem('books', JSON.stringify(books));
    }

    static editBook(title, author, isbn) {
        const books = Store.getBooks();
        books.forEach(function(book){
            if(book.isbn === isbn){
                book.title = title;
                book.author = author;
            }
        });
        localStorage.setItem('books', JSON.stringify(books));
    }
}

// Load book list when DOM is ready
document.addEventListener('DOMContentLoaded', Store.displayBooks());

// Add Book Submit
document.getElementById('book-form').addEventListener('submit', function (e) {
    const title = document.getElementById('title').value,
        author = document.getElementById('author').value,
        isbn = document.getElementById('isbn').value;

    let book;

    // Tentukan tipe buku berdasarkan isi title
    if (title.toLowerCase().includes("audio")) {
        book = new AudioBook(title, author, isbn, "1 hour");
    } else if (title.toLowerCase().includes("ebook")) {
        book = new EBook(title, author, isbn, "2MB");
    } else {
        book = new Book(title, author, isbn);
    }

    const ui = new UI();

    if (title === '' || author === '' || isbn === '') {
        ui.showAlert('Please fill in all fields', 'error');
    } else {
        ui.addBookToList(book);
        Store.addBook(book);
        ui.showAlert('Book Added', 'success');
        ui.clearFields();
    }

    e.preventDefault();
});

// Delete or Edit
document.getElementById('book-list').addEventListener('click', function (e) {
    const ui = new UI();

    if (e.target.className === "delete") {
        ui.deleteBook(e.target);
        Store.removeBook(e.target.parentElement.parentElement.querySelector('.misbn').textContent);
        ui.showAlert('Book Removed', 'success');
    } else if (e.target.className === 'edit') {
        ui.editBook(e.target);
        ui.showModal(ui.uibtitle, ui.uibauthor, ui.uibisbn);
    }

    e.preventDefault();
});

// Edit Form Submit
document.getElementById('mform').addEventListener('submit', function (e) {
    e.preventDefault();

    const mtitle = document.querySelector('#mtitle').value;
    const mauthor = document.querySelector('#mauthor').value;
    const misbn = document.querySelector('#misbn').value;

    const ui = new UI();

    if (mtitle === '' || mauthor === '') {
        ui.showAlert('Please fill in all fields', 'error');
        return;
    } else {
        Store.editBook(mtitle, mauthor, misbn);
        Store.displayBooks();
        ui.showAlert('Book Edited', 'success');
        ui.closeModal();
        ui.clearFields();
    }
});

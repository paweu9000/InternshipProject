<?php
namespace App\Tests\Entity;

use App\Exception\BadBookException;
use App\Model\BookDto;
use App\Repository\BookRepository;
use App\Service\BookService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use App\Entity\Book;

class BookServiceTest extends TestCase
{
    private EntityManagerInterface $entityManagerMock;
    private QueryBuilder $queryBuilderMock;
    private Query $queryMock;
    private BookService $bookService;

    public function setup(): void {
        $this->entityManagerMock = $this->createMock(EntityManager::class);
        $this->queryBuilderMock = $this->createMock(QueryBuilder::class);
        $this->queryMock = $this->createMock(Query::class);
        $this->bookService = new BookService($this->entityManagerMock);
    }

    public function testSaveBookThrowsExceptionWhenLibraryFull() {
        $bookDto = new BookDto("Lord of the Rings", "JRR Tolkien", "9871231234567", 1954);

        $this->entityManagerMock->method('createQueryBuilder')->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->method('getQuery')->willReturn($this->queryMock);
        $this->queryMock->method('getSingleResult')->willReturn(['totalBooks' => 100]);

        $this->expectException(BadBookException::class);
        $this->expectExceptionMessage("The library is full, you cannot add any more books");

        $this->bookService->saveBook($bookDto);
    }

    public function testSaveBookThrowsExceptionWhenThereIsAlreadySameBook() {
        $bookDto = new BookDto("Lord of the Rings", "JRR Tolkien", "9871231234567", 1954);

        $this->entityManagerMock->method('createQueryBuilder')->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->method('getQuery')->willReturn($this->queryMock);
        $this->queryMock->method('getSingleResult')->willReturn(['totalBooks' => 1, 'authorTitleCount' => 1]);

        $this->expectException(BadBookException::class);
        $this->expectExceptionMessage("There is already a book like that in the database");

        $this->bookService->saveBook($bookDto);
    }

    public function testSaveBookThrowsExceptionWhereFiveOfSameAuthor() {
        $bookDto = new BookDto("Lord of the Rings", "JRR Tolkien", "9871231234567", 1954);

        $this->entityManagerMock->method('createQueryBuilder')->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->method('getQuery')->willReturn($this->queryMock);
        $this->queryMock->method('getSingleResult')->willReturn(['totalBooks' => 5, 'authorTitleCount' => 0, 'isbnCount' => 0, 'authorBooksCount' => 5]);

        $this->expectException(BadBookException::class);
        $this->expectExceptionMessage("There are already 5 books of this author");

        $this->bookService->saveBook($bookDto);
    }

    public function testSaveBookThrowsExceptionWhereIsbnNotUnique() {
        $bookDto = new BookDto("Lord of the Rings", "JRR Tolkien", "9871231234567", 1954);

        $this->entityManagerMock->method('createQueryBuilder')->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->method('getQuery')->willReturn($this->queryMock);
        $this->queryMock->method('getSingleResult')->willReturn(['totalBooks' => 5, 'authorTitleCount' => 0, 'isbnCount' => 1, 'authorBooksCount' => 0]);

        $this->expectException(BadBookException::class);
        $this->expectExceptionMessage("There is already a book with provided isbn");

        $this->bookService->saveBook($bookDto);
    }

    public function testSaveBookWithoutException() {
        $bookDto = new BookDto("Lord of the Rings", "JRR Tolkien", "9871231234567", 1954);

        $this->entityManagerMock->method('createQueryBuilder')->willReturn($this->queryBuilderMock);
        $this->queryBuilderMock->method('getQuery')->willReturn($this->queryMock);
        $this->queryMock->method('getSingleResult')->willReturn(['totalBooks' => 99, 'authorTitleCount' => 0, 'isbnCount' => 0, 'authorBooksCount' => 4]);

        $book = $this->bookService->saveBook($bookDto);

        $this->assertNotNull($book);
        $this->assertEquals($bookDto->yearOfPublication, $book->getYearOfPublication());
        $this->assertEquals($bookDto->title, $book->getTitle());
        $this->assertEquals($bookDto->author, $book->getAuthor());
        $this->assertEquals($bookDto->isbn, $book->getIsbn());
    }

    public function testDeleteBook(){
        $book = $this->createMock(Book::class);

        $this->entityManagerMock
            ->method('find')
            ->with(Book::class, 1)
            ->willReturn($book);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('remove')
            ->with($book);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('flush');

        $this->bookService->deleteBook(1);
    }

    public function testDeleteBookDoesNothingIfBookNotFound(){
        $this->entityManagerMock
            ->method('find')
            ->with(Book::class, 1)
            ->willReturn(null);

        $this->entityManagerMock
            ->expects($this->never())
            ->method('remove');

        $this->entityManagerMock
            ->expects($this->never())
            ->method('flush');

        $this->bookService->deleteBook(1);
    }

    public function testGetAllBooks(){
        $books = [$this->createMock(Book::class), $this->createMock(Book::class)];

        $repositoryMock = $this->createMock(BookRepository::class);
        $repositoryMock
            ->method('findAll')
            ->willReturn($books);

        $this->entityManagerMock
            ->method('getRepository')
            ->with(Book::class)
            ->willReturn($repositoryMock);

        $result = $this->bookService->getAllBooks();

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(Book::class, $result);
    }

    public function testUpdateBook(){
        $bookDto = new BookDto("Updated Title", "Updated Author", "2938123456", 2020);

        $book = $this->createMock(Book::class);

        $this->entityManagerMock
            ->method('find')
            ->with(Book::class, 1)
            ->willReturn($book);

        $book->expects($this->once())->method('setAuthor')->with($bookDto->author);
        $book->expects($this->once())->method('setIsbn')->with($bookDto->isbn);
        $book->expects($this->once())->method('setYearOfPublication')->with($bookDto->yearOfPublication);
        $book->expects($this->once())->method('setTitle')->with($bookDto->title);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('persist')
            ->with($book);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('flush');

        $updatedBook = $this->bookService->updateBook($bookDto, 1);

        $this->assertSame($book, $updatedBook);
    }
    
}
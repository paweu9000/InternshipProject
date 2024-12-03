<?php
namespace App\Service;

use App\Entity\Book;
use App\Exception\BadBookException;
use App\Model\BookDto;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

class BookService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function saveBook(BookDto $bookDto): Book {
        if ($bookDto->yearOfPublication < 1900 || $bookDto->yearOfPublication > (int)date("Y") ) {
            throw new BadBookException("The year must be between 1900 and current year");
        }
        
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('
            COUNT(b.id) AS totalBooks,
            SUM(CASE WHEN b.title = :title AND b.author = :author THEN 1 ELSE 0 END) AS authorTitleCount,
            SUM(CASE WHEN b.author = :author THEN 1 ELSE 0 END) AS authorBooksCount,
            SUM(CASE WHEN b.isbn = :isbn THEN 1 ELSE 0 END) AS isbnCount
        ')
        ->from(Book::class, 'b')
        ->setParameter('title', $bookDto->title)
        ->setParameter('author', $bookDto->author)
        ->setParameter('isbn', $bookDto->isbn);

        $result = $queryBuilder->getQuery()->getSingleResult();

        if ($result['totalBooks'] >= 100) {
            throw new BadBookException("The library is full, you cannot add any more books");
        }

        if ($result['authorTitleCount'] > 0) {
            throw new BadBookException("There is already a book like that in the database");
        }

        if ($result['isbnCount']  > 0) {
            throw new BadBookException("There is already a book with provided isbn");
        }

        if ($result['authorBooksCount'] >= 5) {
            throw new BadBookException("There are already 5 books of this author");
        }

        $book = new Book($bookDto);

        $this->entityManager->persist($book);
        $this->entityManager->flush();
        
        return $book;
    }

    public function deleteBook(int $id): void {
        $book = $this->entityManager->find(Book::class, $id);
        if (!$book) return;
        $this->entityManager->remove($book);
        $this->entityManager->flush();
    }

    public function getAllBooks() {
        $books = $this->entityManager->getRepository(Book::class)->findAll();

        return $books;
    }

    public function updateBook(BookDto $bookDto, int $id): Book {
        if ($bookDto->yearOfPublication < 1900 || $bookDto->yearOfPublication > (int)date("Y") ) {
            throw new BadBookException("The year must be between 1900 and current year");
        }
        $bookToUpdate = $this->entityManager->find(Book::class, $id);
        if ($bookToUpdate) {
            $bookToUpdate->setAuthor( $bookDto->author);
            $bookToUpdate->setIsbn( $bookDto->isbn);
            $bookToUpdate->setYearOfPublication( $bookDto->yearOfPublication);
            $bookToUpdate->setTitle( $bookDto->title);

            $this->entityManager->persist($bookToUpdate);
            $this->entityManager->flush();

            return $bookToUpdate;
        }
        else {
            throw new BadBookException("Book with provided id doesn't exist");
        }
    }
}
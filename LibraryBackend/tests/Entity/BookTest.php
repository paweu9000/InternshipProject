<?php

namespace App\Tests\Entity;

use App\Entity\Book;
use App\Model\BookDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class BookTest extends TestCase
{
    public function testBookProperties(): void
    {
        $bookDto = new BookDto('The Hobbit', 'J.R.R. Tolkien', '9780547928227', 1937);
        $book = new Book($bookDto);

        $this->assertEquals('The Hobbit', $book->getTitle());
        $this->assertEquals('J.R.R. Tolkien', $book->getAuthor());
        $this->assertEquals('9780547928227', $book->getIsbn());
        $this->assertEquals(1937, $book->getYearOfPublication());
    }

    public function testValidation(): void
    {
        $validator = Validation::createValidatorBuilder()
                    ->enableAttributeMapping()
                    ->getValidator();
        
                            //blank, min 3| blank, min 3| invalid regex isbn|range not between 1900-now
        $bookDto = new BookDto('', '', '123', 1899);
        
        $violations = $validator->validate($bookDto);

        $this->assertCount(6, $violations);

        $book = new Book($bookDto);

        $violations = $validator->validate($book);

        $this->assertCount(6, $violations);
    }
}
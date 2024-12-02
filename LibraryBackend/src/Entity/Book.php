<?php

namespace App\Entity;

use App\Model\BookDto;
use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\Table(name: "book", uniqueConstraints: [
    new ORM\UniqueConstraint(name: "unique_isbn", columns: ["isbn"])
])]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, minMessage: 'Title is too short.')]
    #[Assert\Length(max: 255, maxMessage: 'Title is too long.')]
    private string $title;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, minMessage: 'Author name is too short.')]
    #[Assert\Length(max: 255, maxMessage: 'Author name is too long.')]
    private string $author;

    #[ORM\Column(length: 13)]
    #[Assert\Regex(
        pattern: '/^\d{10}(\d{3})?$/',
        message: 'The ISBN must be either 10 or 13 digits long.'
    )]
    private string $isbn;

    #[ORM\Column]
    #[Assert\Range(
        min: 1900,
        max: 'now',
        notInRangeMessage: 'The year must be between {{min}} and {{max}}.'
    )]
    private int $yearOfPublication;

    public function __construct(BookDto $bookDto) {
        $this->setAuthor($bookDto->author);
        $this->setIsbn($bookDto->isbn);
        $this->setTitle($bookDto->title);
        $this->setYearOfPublication($bookDto->yearOfPublication);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getYearOfPublication(): ?int
    {
        return $this->yearOfPublication;
    }

    public function setYearOfPublication(int $yearOfPublication): static
    {
        $this->yearOfPublication = $yearOfPublication;

        return $this;
    }
}

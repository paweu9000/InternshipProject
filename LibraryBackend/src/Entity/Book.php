<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, minMessage: 'Title is too short.')]
    #[Assert\Length(max: 255, maxMessage: 'Title is too long.')]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, minMessage: 'Author name is too short.')]
    #[Assert\Length(max: 255, maxMessage: 'Author name is too long.')]
    private ?string $author = null;

    #[ORM\Column(length: 13)]
    #[Assert\Unique]
    #[Assert\Regex(
        pattern: '/^\d{10}(\d{3})?$/',
        message: 'The ISBN must be either 10 or 13 digits long.'
    )]
    private ?string $isbn = null;

    #[ORM\Column]
    #[Assert\Range(
        min: 1900,
        max: 'now',
        notInRangeMessage: 'The year must be between {{min}} and {{max}}.'
    )]
    private ?int $year_of_publication = null;

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
        return $this->year_of_publication;
    }

    public function setYearOfPublication(int $year_of_publication): static
    {
        $this->year_of_publication = $year_of_publication;

        return $this;
    }
}

<?php
namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class BookDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 3, minMessage: 'Title is too short.')]
        #[Assert\Length(max: 255, maxMessage: 'Title is too long.')]
        public readonly string $title,
        #[Assert\NotBlank]
        #[Assert\Length(min: 3, minMessage: 'Title is too short.')]
        #[Assert\Length(max: 255, maxMessage: 'Title is too long.')]
        public readonly string $author,
        #[Assert\Regex(
            pattern: '/^\d{10}(\d{3})?$/',
            message: 'The ISBN must be either 10 or 13 digits long.'
        )]
        public readonly string $isbn,
        #[Assert\Range(
            min: 1900,
            max: 'now',
            notInRangeMessage: 'The year must be between {{min}} and {{max}}.'
        )]
        public readonly int $yearOfPublication
    ){}
}
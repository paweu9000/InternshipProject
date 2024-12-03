<?php
namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class PublicationYear extends Constraint
{
    public string $message = '';

    public function __construct(
        ?array $groups = null,
        mixed $payload = null
    ) {
        $this->message = 'The year must be between 1900 and ' . (int)date('Y');
        parent::__construct([], $groups, $payload);
    }
}
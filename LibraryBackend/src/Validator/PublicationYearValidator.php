<?php
namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PublicationYearValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof PublicationYear) {
            throw new UnexpectedTypeException($constraint, PublicationYear::class);
        }

        if ($value >= 1900 && $value <= (int)date('Y')) {
            return;
        }

        $this->context->buildViolation($constraint->message)->addViolation();
    }
}
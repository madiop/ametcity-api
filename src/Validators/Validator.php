<?php

namespace App\Validators;

use Symfony\Component\Validator\ConstraintViolationList;
use App\Exception\ResourceValidationException;

class Validator 
{
    public function validate(ConstraintViolationList $violations)
    {
        if (count($violations)) {
            $message = 'Données fournies invalides: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Champ %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }
    }
}
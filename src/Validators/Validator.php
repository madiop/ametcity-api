<?php

namespace App\Validators;

use Symfony\Component\Validator\ConstraintViolationList;
use App\Exception\ResourceValidationException;

class Validator 
{
    public function validate(ConstraintViolationList $violations)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }
    }
}
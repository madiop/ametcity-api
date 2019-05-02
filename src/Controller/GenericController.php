<?php

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Doctrine\ORM\EntityManagerInterface;

use App\Validators\Validator;

class GenericController extends FOSRestController
{
    protected $em;
    protected $dataValidator;

    public function __construct(EntityManagerInterface $em,
                                Validator $validator)
    {
        $this->em = $em;
        $this->dataValidator = $validator;
    }
}
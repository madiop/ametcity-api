<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EntreprisesController extends AbstractController
{
    /**
     * @Route("/entreprises", name="entreprises")
     */
    public function index()
    {
        return $this->render('entreprises/index.html.twig', [
            'controller_name' => 'EntreprisesController',
        ]);
    }
}

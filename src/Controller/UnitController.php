<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UnitController extends AbstractController{
    #[Route('/unit', name: 'app_unit')]
    public function index(): Response
    {
        return $this->render('unit/index.html.twig', [
            'controller_name' => 'UnitController',
        ]);
    }
}

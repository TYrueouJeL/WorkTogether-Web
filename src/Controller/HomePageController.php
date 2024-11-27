<?php

namespace App\Controller;

use App\Repository\UnitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index(UnitRepository $unitRepository): Response
    {
        return $this->render('home_page/index.html.twig', [
            'unitList' => $unitRepository->findAll(),
        ]);
    }
}

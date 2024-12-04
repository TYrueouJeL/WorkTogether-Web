<?php

namespace App\Controller;

use App\Repository\PackRepository;
use App\Repository\UnitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index(PackRepository $packRepository): Response
    {
        return $this->render('home_page/index.html.twig', [
            'packList' => $packRepository->findAll(),
        ]);
    }
}

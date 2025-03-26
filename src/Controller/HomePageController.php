<?php

namespace App\Controller;

use App\Repository\PackRepository;
use App\Repository\UnitRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index(PackRepository $packRepository): Response
    {
        $user = $this->getUser();

        if ($user) {
            $userFirstname = $user->getFirstname();
            $userLastname = $user->getLastname();
            $userId = $userFirstname . ' ' . $userLastname;
        }
        else {
            $userId = null;
        }

        return $this->render('home_page/index.html.twig', [
            'packList' => $packRepository->findAll(),
            'userId' => $userId,
        ]);
    }
}

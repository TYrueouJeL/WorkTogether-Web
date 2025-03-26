<?php

namespace App\Controller;

use App\Entity\Unit;
use App\Repository\CommandedUnitRepository;
use App\Repository\UnitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UnitController extends AbstractController{
    #[Route('/unit/{id}', name: 'app_unit')]
    public function index(UnitRepository $unitRepository, int $id): Response
    {
        $user = $this->getUser();
        $unit = $unitRepository->find($id);

        if ($user == null) {
            return $this->redirect('/');
        }

        return $this->render('unit/index.html.twig', [
            'unit' => $unit,
        ]);
    }
}

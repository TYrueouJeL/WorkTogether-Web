<?php

namespace App\Controller;

use App\Entity\Unit;
use App\Repository\CommandedUnitRepository;
use App\Repository\InterventionRepository;
use App\Repository\UnitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UnitController extends AbstractController{
    #[Route('/unit/{id}', name: 'app_unit')]
    public function index(UnitRepository $unitRepository, CommandedUnitRepository $commandedUnitRepository, int $id): Response
    {
        $user = $this->getUser();
        $unit = $unitRepository->find($id);
        $commandedUnit = $commandedUnitRepository->findOneBy(['unit' => $unit]);
        $order = $commandedUnit->getOrders();

        if ($user == null || $user != $order->getCustomer()) {
            return $this->redirect('/');
        }

        return $this->render('unit/index.html.twig', [
            'unit' => $unit,
        ]);
    }

    #[Route('/unit/{id}/turn-on', name: 'app_unit_turn_on')]
    public function turnOn(int $id, UnitRepository $unitRepository): Response
    {
        $unitRepository->turnOnUnit($id);

        return $this->redirect('/unit/' . $id);
    }

    #[Route('/unit/{id}/turn-off', name: 'app_unit_turn_off')]
    public function turnOff(int $id, UnitRepository $unitRepository): Response
    {
        $unitRepository->turnOffUnit($id);

        return $this->redirect('/unit/' . $id);
    }

    #[Route('/unit/{id}/interventions', name: 'app_unit_intervention')]
    public function intervention(int $id, UnitRepository $unitRepository, InterventionRepository $interventionRepository): Response
    {
        $unit = $unitRepository->find($id);
        $interventions = $interventionRepository->findBy(['unit' => $unit]);

        return $this->render('unit/interventions.html.twig', [
            'unit' => $unit,
            'interventions' => $interventions,
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Unit;
use App\Entity\Usage;
use App\Form\UsageFormType;
use App\Repository\CommandedUnitRepository;
use App\Repository\InterventionRepository;
use App\Repository\UnitRepository;
use App\Repository\UsageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Config\Framework\SerializerConfig;

final class UnitController extends AbstractController{
    #[Route('/unit/{id}', name: 'app_unit')]
    public function index(UnitRepository $unitRepository, CommandedUnitRepository $commandedUnitRepository, int $id): Response
    {
        $user = $this->getUser();
        $unit = $unitRepository->find($id);
        $commandedUnit = $commandedUnitRepository->findActiveByUnit($unit);
        $order = $commandedUnit->getOrders();

        if ($user == null || $user != $order->getCustomer()) {
            return $this->redirect('/');
        }

        return $this->render('unit/index.html.twig', [
            'unit' => $unit,
            'order' => $order,
        ]);
    }

    #[Route('/unit/{id}/turn-on', name: 'app_unit_turn_on')]
    public function turnOn(int $id, UnitRepository $unitRepository, CommandedUnitRepository $commandedUnitRepository): Response
    {
        $user = $this->getUser();
        $unit = $unitRepository->find($id);
        $commandedUnit = $commandedUnitRepository->findOneBy(['unit' => $unit]);
        $order = $commandedUnit->getOrders();

        if ($user == null || $user != $order->getCustomer()) {
            return $this->redirect('/');
        }

        $unitRepository->turnOnUnit($id);

        return $this->redirect('/unit/' . $id);
    }

    #[Route('/unit/{id}/turn-off', name: 'app_unit_turn_off')]
    public function turnOff(int $id, UnitRepository $unitRepository, CommandedUnitRepository $commandedUnitRepository): Response
    {
        $user = $this->getUser();
        $unit = $unitRepository->find($id);
        $commandedUnit = $commandedUnitRepository->findOneBy(['unit' => $unit]);
        $order = $commandedUnit->getOrders();

        if ($user == null || $user != $order->getCustomer()) {
            return $this->redirect('/');
        }

        $unitRepository->turnOffUnit($id);

        return $this->redirect('/unit/' . $id);
    }

    #[Route('/unit/{id}/interventions', name: 'app_unit_intervention')]
    public function intervention(int $id, UnitRepository $unitRepository, InterventionRepository $interventionRepository, CommandedUnitRepository $commandedUnitRepository): Response
    {
        $user = $this->getUser();
        $unit = $unitRepository->find($id);
        $commandedUnit = $commandedUnitRepository->findOneBy(['unit' => $unit]);
        $order = $commandedUnit->getOrders();

        if ($user == null || $user != $order->getCustomer()) {
            return $this->redirect('/');
        }

        $unit = $unitRepository->find($id);
        $interventions = $interventionRepository->findBy(['unit' => $unit]);

        return $this->render('unit/interventions.html.twig', [
            'unit' => $unit,
            'interventions' => $interventions,
        ]);
    }

    #[Route('/unit/{id}/usage', name: 'app_unit_usage')]
    public function usage(int $id, UnitRepository $unitRepository, UsageRepository $usageRepository, Request $request, EntityManagerInterface $entityManager, CommandedUnitRepository $commandedUnitRepository): Response
    {
        $user = $this->getUser();
        $unit = $unitRepository->find($id);
        $commandedUnit = $commandedUnitRepository->findOneBy(['unit' => $unit]);
        $order = $commandedUnit->getOrders();

        if ($user == null || $user != $order->getCustomer()) {
            return $this->redirect('/');
        }

        $unit = $unitRepository->find($id);

        $usages = $usageRepository->findAll();
        $usageChoices = [];
        foreach ($usages as $usage) {
            $usageChoices[$usage->getType()] = $usage->getId();
        }

        $form = $this->createForm(UsageFormType::class, null, ['usage_choices' => $usageChoices]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usageId = $form->get('type')->getData();
            $usage = $usageRepository->find($usageId);
            $unit->setUsage($usage);

            $entityManager->persist($unit);
            $entityManager->flush();

            return $this->redirect('/unit/' . $id);
        }

        return $this->render('unit/usage.html.twig', [
            'unit' => $unit,
            'form' => $form,
        ]);
    }

    #[Route('/unit/{id}/api', name: 'app_unit_api', methods: ['GET'])]
    public function unitAPI(int $id, UnitRepository $unitRepository, SerializerInterface $serializer): JsonResponse
    {
        $unit = $unitRepository->find($id);

        if (!$unit) {
            return new JsonResponse(['error' => 'Unit not found'], 404);
        }

        $data = $serializer->normalize($unit, null, ['groups' => 'unit:read']);

        return new JsonResponse($data, 200);
    }
}

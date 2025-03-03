<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderReservationFormType;
use App\Form\OrderTestFormType;
use App\Repository\PackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CommandController extends AbstractController
{
    #[IsGranted('ROLE_CLIENT')]
    #[Route('/command/{id}', name: 'app_command_reservation')]
    public function commandReservation(EntityManagerInterface $entityManager, int $id, PackRepository $packRepository, Request $request): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderReservationFormType::class, $order);

        $form->handleRequest($request);

        $user = $this->getUser();

        // Réception du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $startDate = new \DateTime();
            $order->setStartDate($startDate);

            if ($order->isAnnual()) {
                $endDate = (clone $startDate)->modify('+12 month');
                $order->setEndDate($endDate);
            }
            else {
                $endDate = (clone $startDate)->modify('+1 month');
                $order->setEndDate($endDate);
            }

            // Définition du pack avec le paramètre de l'url
            $pack = $packRepository->find($id);

            // Si le pack n'existe pas alors on retourne une erreur
            if (!$pack) {
                throw $this->createNotFoundException("Le pack n'existe pas");
            }

            $order->setPack($pack);
            $order->setCustomer($user);

            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('app_home_page');
        }

        return $this->render('command/reservation.html.twig', [
            'form' => $form,
            'pack' => $packRepository->find($id),
        ]);
    }

    #[IsGranted('ROLE_CLIENT')]
    #[Route('/testcommand/{id}', name: 'app_command_reservation_test')]
    public function testCommandReservation(EntityManagerInterface $entityManager, int $id, PackRepository $packRepository, Request $request): Response
    {
        $order = new Order();

        $startDate = new \DateTime();
        $order->setStartDate($startDate);

        if ($order->isAnnual()) {
            $endDate = (clone $startDate)->modify('+12 month');
            $order->setEndDate($endDate);
        }
        else {
            $endDate = (clone $startDate)->modify('+1 month');
            $order->setEndDate($endDate);
        }

        $order->setAnnual(false);

        $user = $this->getUser();
        $order->setCustomer($user);

        $pack = $packRepository->find($id);

        if (!$pack) {
            echo "Pack invalide";
            throw $this->createNotFoundException("Le pack n'existe pas");
        }
        else
        {
            echo "Formulaire valide";
        }

        $order->setPack($pack);

        $form = $this->createForm(OrderTestFormType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            echo "Formulaire valide";

            $entityManager->persist($order);
            $entityManager->flush();
        }

        return $this->render('command/reservation.html.twig', [
            'form' => $form,
            'pack' => $packRepository->find($id),
        ]);
    }
}

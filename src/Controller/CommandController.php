<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderReservationFormType;
use App\Repository\PackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Date;

class CommandController extends AbstractController
{
    #[Route('/command', name: 'app_command')]
    public function index(): Response
    {
        return $this->render('command/index.html.twig', [
        ]);
    }

    #[Route('/command/{id}', name: 'app_command_reservation')]
    public function commandReservation(EntityManagerInterface $entityManager, int $id, PackRepository $packRepository): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderReservationFormType::class, $order);

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

            $entityManager->persist($order);
            $entityManager->flush();
            return $this->redirect('/');
        }

        return $this->render('command/reservation.html.twig', [
            'form' => $form,
        ]);
    }
}

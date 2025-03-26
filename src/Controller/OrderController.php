<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController{
    #[Route('/order/{id}', name: 'app_order_details')]
    public function details(OrderRepository $orderRepository, int $id): Response
    {
        $user = $this->getUser();
        $order = $orderRepository->find($id);

        if ($user == null || $order == null || $order->getCustomer() != $user) {
            return $this->redirect('/');
        }

        $unitsByBay = [];
        foreach ($order->getCommandedUnits() as $commandedUnit) {
            $unit = $commandedUnit->getUnit();
            $bay = $unit->getBay();
            $unitsByBay[$bay->getReference()][] = $unit;
        }

        return $this->render('order/details.html.twig', [
            'order' => $order,
            'unitsByBay' => $unitsByBay,
            'customerId' => $user->getId(),
        ]);
    }

    #[Route('/order/{id}/remove-verification', name: 'app_order_remove_verification')]
    public function removeVerification(int $id, OrderRepository $orderRepository): Response
    {
        $customerId = $this->getUser()->getId();

        return $this->render('order/removeVerification.html.twig', [
            'order' => $orderRepository->find($id),
            'customerId' => $customerId,
        ]);
    }

    #[Route('/order/{id}/remove', name: 'app_order_remove')]
    public function remove(EntityManagerInterface $entityManager, int $id): Response
    {
        $order = $entityManager->getRepository(Order::class)->find($id);
        $order->setEndDate(new \DateTime());

        $entityManager->persist($order);
        $entityManager->flush();

        return $this->redirectToRoute('app_home_page');
    }
}

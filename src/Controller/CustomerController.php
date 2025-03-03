<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Repository\PackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CustomerController extends AbstractController{
    #[IsGranted('ROLE_CLIENT')]
    #[Route('/customer/{id}', name: 'app_customer_account')]
    public function account(OrderRepository $orderRepository, int $id): Response
    {
        $user = $this->getUser();

        $orders = $orderRepository->findBy(['customer' => $id]);

        $packs = [];
        foreach ($orders as $order) {
            $packs[] = $order->getPack();
        }

        return  $this->render('customer/account.html.twig', [
            'user' => $user,
            'packs' => $packs,
            'orders' => $orders,
        ]);
    }
}

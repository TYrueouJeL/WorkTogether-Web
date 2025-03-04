<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController{
    #[Route('/order/{id}', name: 'app_order_details')]
    public function details(OrderRepository $orderRepository, int $id): Response
    {
        $order = $orderRepository->find($id);

        $user = $this->getUser();

        if ($user != $order->getCustomer()) {
            return $this->redirect('/');
        }

        return $this->render('order/details.html.twig', [
            'order' => $order,
        ]);
    }
}

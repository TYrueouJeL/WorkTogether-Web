<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Repository\PackRepository;
use App\Repository\UnitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\Date;

class CustomerController extends AbstractController
{
    #[Route('/customer/{id}', name: 'app_customer_account')]
    public function account(OrderRepository $orderRepository, int $id): Response
    {
        $user = $this->getUser();

        if ($user == null || $user->getId() != $id)
        {
            return $this->redirect('/');
        }

        $orders = $orderRepository->findBy(['customer' => $id]);

        $packs = [];
        foreach ($orders as $order) {
            $packs[] = $order->getPack();
        }

        return  $this->render('customer/account.html.twig', [
            'user' => $user,
            'packs' => $packs,
            'orders' => $orders,
            'now' => new \DateTime(),
        ]);
    }

//    #[Route('/customer/{id}/free-units', name: 'app_customer_free_units')]
//    public function freeUnits(UnitRepository $unitRepository, int $id): Response
//    {
//        $user = $this->getUser();
//
//        if ($user == null || $user->getId() != $id) {
//            return $this->redirect('/');
//        }
//
//        $unitRepository->freeUnits();
//
//        return $this->redirectToRoute('app_customer_account', ['id' => $id]);
//    }
}

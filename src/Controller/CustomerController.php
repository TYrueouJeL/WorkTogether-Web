<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\OrderRepository;
use App\Repository\PackRepository;
use App\Repository\UnitRepository;
use App\Repository\UsageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\Request;

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

    #[Route('/customer/{id}/data', name: 'app_customer_data')]
    public function data(int $id): Response
    {
        $user = $this->getUser();

        if ($user == null || $user->getId() != $id)
        {
            return $this->redirect('/');
        }

        return $this->render('customer/data.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/customer/{id}/data/modify/{option}')]
    public function modify(int $id, string $option, Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($user == null || $user->getId() != $id) {
            return $this->redirect('/');
        }

        if ($request->isMethod('POST')) {
            switch ($option) {
                case 'firstname' :
                    $user->setFirstname($request->request->get('firstname'));
                    break;
                case 'lastname' :
                    $user->setLastname($request->request->get('lastname'));
                    break;
                case 'password' :
                    $plainPassword = $request->request->get('password');
                    $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                    $user->setPassword($hashedPassword);
                    break;
                default :
                    return $this->redirect('/customer/' . $id . '/data');
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirect('/customer/' . $id . '/data');
        }

        return $this->render('customer/modify.html.twig', [
            'id' => $id,
            'option' => $option,
            'user' => $user,
        ]);
    }

    #[Route('/customer/{id}/delete', name: 'app_customer_delete')]
    public function delete(int $id): Response
    {
        $user = $this->getUser();

        return $this->render('customer/delete.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/customer/{id}/suppression', name: 'app_customer_suppression')]  
    public function suppression(int $id, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if ($user == null || $user->getId() != $id) {
            return $this->redirect('/');
        }

        $user->setEmail('anonymous');
        $user->setPassword('');
        $user->setRole(0);
        $user->setFirstname('anonymous');
        $user->setLastname('anonymous');

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirect('/logout');
    }
}

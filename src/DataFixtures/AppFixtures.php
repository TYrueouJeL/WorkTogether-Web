<?php

namespace App\DataFixtures;

use App\Entity\Bay;
use App\Entity\CommandedUnit;
use App\Entity\Customer;
use App\Entity\Intervention;
use App\Entity\Order;
use App\Entity\Pack;
use App\Entity\State;
use App\Entity\Type;
use App\Entity\Unit;
use App\Entity\Usage;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        // fixtures des 30 baies

        // for ($i = 1; $i <= 30; $i++) {
        //     $bay = new Bay();
        //     if ($i < 10) {
        //         $bay->setReference('B00' . $i);
        //     } else {
        //         $bay->setReference('B0' . $i);
        //     }
        //     $manager->persist($bay);
        // }

        $usage = new Usage();
        $usage->setType('Site Web');
        $usage->setColor('#007bff');
        $usageTab[] = $usage;

        $usage = new Usage();
        $usage->setType('Application Mobile');
        $usage->setColor('#28a745');
        $usageTab[] = $usage;

        $usage = new Usage();
        $usage->setType('Application Bureau');
        $usage->setColor('#ffc107');
        $usageTab[] = $usage;

        $usage = new Usage();
        $usage->setType('Inactive');
        $usage->setColor('#6c757d');
        $usageTab[] = $usage;

        $usage = new Usage();
        $usage->setType('Indisponible');
        $usage->setColor('#000000');
        $usageTab[] = $usage;

        $pack = new Pack();
        $pack->setName('Pack Base');
        $pack->setNbrUnits(1);
        $pack->setEnable(true);
        $pack->setPrice(10);
        $pack->setAnnualReductionPercentage("20");
        $packTab[] = $pack;

        $pack = new Pack();
        $pack->setName('Pack Start-up');
        $pack->setNbrUnits(10);
        $pack->setEnable(true);
        $pack->setPrice(100);
        $pack->setAnnualReductionPercentage("30");
        $packTab[] = $pack;

        $pack = new Pack();
        $pack->setName('Pack PME');
        $pack->setNbrUnits(21);
        $pack->setEnable(true);
        $pack->setPrice(200);
        $pack->setAnnualReductionPercentage("40");
        $packTab[] = $pack;

        $pack = new Pack();
        $pack->setName('Pack Entreprise');
        $pack->setNbrUnits(42);
        $pack->setEnable(true);
        $pack->setPrice(400);
        $pack->setAnnualReductionPercentage("50");
        $packTab[] = $pack;

        $pack = new Pack();
        $pack->setName('Pack Entreprise +');
        $pack->setNbrUnits(84);
        $pack->setEnable(false);
        $pack->setPrice(800);
        $pack->setAnnualReductionPercentage("60");
        $packTab[] = $pack;

        $type = new Type();
        $type->setType('Maintenance');
        $typeTab[] = $type;

        $type = new Type();
        $type->setType('Développement');
        $typeTab[] = $type;

        $state = new State();
        $state->setState('Allumée');
        $stateTab[] = $state;

        $state = new State();
        $state->setState('Éteinte');
        $stateTab[] = $state;

        $state = new State();
        $state->setState('Indisponible');
        $stateTab[] = $state;

        for ($i = 1; $i <= 10; $i++) {
            $bay = new Bay();
            if ($i < 10) {
                $bay->setReference('B' . str_pad($i, 3, '0', STR_PAD_LEFT));
            } else {
                $bay->setReference('B' . str_pad($i, 3, '0', STR_PAD_LEFT));
            }
            $bayTab[] = $bay;
            for ($j = 1; $j <= 42; $j++) {
                if ($j < 10) {
                    $unit = new Unit();
                    $unit->setReference('B' . str_pad($i, 3, '0', STR_PAD_LEFT) . 'U' . str_pad($j, 3, '0', STR_PAD_LEFT));
                    $unit->setBay($bayTab[$i - 1]);
                    $unit->setState($stateTab[rand(0, 1)]);
                    $unit->setUsage($usageTab[rand(0, 3)]);
                    $unitTab[] = $unit;
                } else {
                    $unit = new Unit();
                    $unit->setReference('B' . str_pad($i, 3, '0', STR_PAD_LEFT) . 'U' . str_pad($j, 3, '0', STR_PAD_LEFT));
                    $unit->setBay($bayTab[$i - 1]);
                    $unit->setState($stateTab[rand(0, 1)]);
                    $unit->setUsage($usageTab[rand(0, 3)]);
                    $unitTab[] = $unit;
                }
            }
        }

        $bay = new Bay();
        $bay->setReference('B031');
        $bayTab[] = $bay;

        $intervention = new Intervention();
        $intervention->setTitle('Intervention 1');
        $intervention->setDescription('Description de l\'intervention 1');
        $intervention->setType($typeTab[0]);
        $intervention->setUnit($unitTab[0]);
        $intervention->setDate(new \DateTime());
        $interventionTab[] = $intervention;

        $intervention = new Intervention();
        $intervention->setTitle('Intervention 2');
        $intervention->setDescription('Description de l\'intervention 2');
        $intervention->setType($typeTab[0]);
        $intervention->setUnit($unitTab[0]);
        $intervention->setDate(new \DateTime());
        $interventionTab[] = $intervention;

        $intervention = new Intervention();
        $intervention->setTitle('Intervention 3');
        $intervention->setDescription('Description de l\'intervention 3');
        $intervention->setType($typeTab[0]);
        $intervention->setUnit($unitTab[12]);
        $intervention->setDate(new \DateTime());
        $interventionTab[] = $intervention;

        $customer = new Customer();
        $customer->setEmail('noemye@noemye.com');
        $customer->setPassword('$2y$13$qwX3N3XB.9Z/VPuA/wqGkOzkM90yGmyOTb.XWcBSdJQUaixP1osZK');
        $customer->setFirstname('Noemye');
        $customer->setLastname('Kiso');
        $customer->setRole(1);
        $customerTab[] = $customer;

        $customer = new Customer();
        $customer->setEmail('antonin@antonin.com');
        $customer->setPassword('$2y$13$qwX3N3XB.9Z/VPuA/wqGkOzkM90yGmyOTb.XWcBSdJQUaixP1osZK');
        $customer->setFirstname('Antonin');
        $customer->setLastname('Oracle');
        $customer->setRole(1);
        $customerTab[] = $customer;

        $customer = new Customer();
        $customer->setEmail('remig@remig.com');
        $customer->setPassword('$2y$13$qwX3N3XB.9Z/VPuA/wqGkOzkM90yGmyOTb.XWcBSdJQUaixP1osZK');
        $customer->setFirstname('Remi');
        $customer->setLastname('Guerin');
        $customer->setRole(4);
        $customerTab[] = $customer;

        $customer = new Customer();
        $customer->setEmail('admin@admin.com');
        $customer->setPassword('$2y$13$qwX3N3XB.9Z/VPuA/wqGkOzkM90yGmyOTb.XWcBSdJQUaixP1osZK');
        $customer->setFirstname('Admin');
        $customer->setLastname('Admin');
        $customer->setRole(4);
        $customerTab[] = $customer;

        $customer = new Customer();
        $customer->setEmail('tech@tech.com');
        $customer->setPassword('$2y$13$qwX3N3XB.9Z/VPuA/wqGkOzkM90yGmyOTb.XWcBSdJQUaixP1osZK');
        $customer->setFirstname('Tech');
        $customer->setLastname('Tech');
        $customer->setRole(3);
        $customerTab[] = $customer;

        $customer = new Customer();
        $customer->setEmail('compta@compta.com');
        $customer->setPassword('$2y$13$qwX3N3XB.9Z/VPuA/wqGkOzkM90yGmyOTb.XWcBSdJQUaixP1osZK');
        $customer->setFirstname('Compta');
        $customer->setLastname('Compta');
        $customer->setRole(2);
        $customerTab[] = $customer;

        $order = new Order();
        $order->setStartDate(new \DateTime('2025-01-01'));
        $order->setEndDate(new \DateTime('2026-01-01'));
        $order->setDuration(12);
        $order->setAnnual(true);
        $order->setPack($packTab[0]);
        $order->setCustomer($customerTab[1]);
        $order->setPrice(120 * 0.8);
        $orderTab[] = $order;

        $order = new Order();
        $order->setStartDate(new \DateTime('2021-01-01'));
        $order->setEndDate(new \DateTime('2022-01-01'));
        $order->setDuration(12);
        $order->setAnnual(true);
        $order->setPack($packTab[1]);
        $order->setCustomer($customerTab[1]);
        $order->setPrice(100 * 0.7);
        $orderTab[] = $order;

        $order = new Order();
        $order->setStartDate(new \DateTime('2025-03-01'));
        $order->setEndDate(new \DateTime('2025-04-01'));
        $order->setDuration(1);
        $order->setAnnual(false);
        $order->setPack($packTab[0]);
        $order->setCustomer($customerTab[1]);
        $order->setPrice(10);
        $orderTab[] = $order;

        $order = new Order();
        $order->setStartDate(new \DateTime('2025-03-25'));
        $order->setEndDate(new \DateTime('2025-04-25'));
        $order->setDuration(1);
        $order->setAnnual(false);
        $order->setPack($packTab[1]);
        $order->setCustomer($customerTab[1]);
        $order->setPrice(100);
        $orderTab[] = $order;

        $commandedunit = new CommandedUnit();
        $commandedunit->setOrders($orderTab[0]);
        $commandedunit->setUnit($unitTab[0]);
        $commandedunitTab[] = $commandedunit;

        for ($i = 1; $i <= 10; $i++) {
            $commandedunit = new CommandedUnit();
            $commandedunit->setOrders($orderTab[1]);
            $commandedunit->setUnit($unitTab[$i]);
            $commandedunitTab[] = $commandedunit;
        }

        $commandedunit = new CommandedUnit();
        $commandedunit->setOrders($orderTab[2]);
        $commandedunit->setUnit($unitTab[11]);
        $commandedunitTab[] = $commandedunit;

        for ($i = 1; $i <= 10; $i++) {
            $commandedunit = new CommandedUnit();
            $commandedunit->setOrders($orderTab[3]);
            $commandedunit->setUnit($unitTab[$i]);
            $commandedunitTab[] = $commandedunit;
        }

        foreach ($stateTab as $state) {
            $manager->persist($state);
        }

        foreach ($usageTab as $usage) {
            $manager->persist($usage);
        }

        foreach ($packTab as $pack) {
            $manager->persist($pack);
        }

        foreach ($typeTab as $type) {
            $manager->persist($type);
        }

        foreach ($bayTab as $bay) {
            $manager->persist($bay);
        }

        foreach ($unitTab as $unit) {
            $manager->persist($unit);
        }

        foreach ($interventionTab as $intervention) {
            $manager->persist($intervention);
        }

        foreach ($customerTab as $customer) {
            $manager->persist($customer);
        }

        foreach ($orderTab as $order) {
            $manager->persist($order);
        }

        foreach ($commandedunitTab as $commandedunit) {
            $manager->persist($commandedunit);
        }

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Bay;
use App\Entity\Intervention;
use App\Entity\Pack;
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

        for ($i = 1; $i <= 30; $i++) {
            $bay = new Bay();
            if ($i < 10) {
                $bay->setReference('B00' . $i);
            } else {
                $bay->setReference('B0' . $i);
            }
            $manager->persist($bay);
        }

        $usage = new Usage();
        $usage->setType('Site Web');
        $usage->setColor('bleu');
        $manager->persist($usage);

        $usage = new Usage();
        $usage->setType('Application Mobile');
        $usage->setColor('vert');
        $manager->persist($usage);

        $usage = new Usage();
        $usage->setType('Application Desktop');
        $usage->setColor('rouge');
        $manager->persist($usage);

        $pack = new Pack();
        $pack->setName('Pack Base');
        $pack->setNbrUnits(1);
        $pack->setEnable(true);
        $pack->setPrice(10);
        $pack->setAnnualReductionPercentage("20");
        $manager->persist($pack);

        $pack = new Pack();
        $pack->setName('Pack Start-up');
        $pack->setNbrUnits(10);
        $pack->setEnable(true);
        $pack->setPrice(100);
        $pack->setAnnualReductionPercentage("30");
        $manager->persist($pack);

        $pack = new Pack();
        $pack->setName('Pack PME');
        $pack->setNbrUnits(21);
        $pack->setEnable(true);
        $pack->setPrice(200);
        $pack->setAnnualReductionPercentage("40");
        $manager->persist($pack);

        $pack = new Pack();
        $pack->setName('Pack Entreprise');
        $pack->setNbrUnits(42);
        $pack->setEnable(true);
        $pack->setPrice(400);
        $pack->setAnnualReductionPercentage("50");
        $manager->persist($pack);

        $pack = new Pack();
        $pack->setName('Pack Entreprise +');
        $pack->setNbrUnits(84);
        $pack->setEnable(false);
        $pack->setPrice(800);
        $pack->setAnnualReductionPercentage("60");
        $manager->persist($pack);

        $user = new User();
        $user->setEmail('remi@remi.com');
        $user->setPassword('remi');
        $user->setRole(['ROLE_CLIENT']);
        $manager->persist($user);

        $user = new User();
        $user->setEmail('admin@admin.com');
        $user->setPassword('admin');
        $user->setRole(['ROLE_CLIENT', 'ROLE_ADMIN']);
        $manager->persist($user);

        $type = new Type();
        $type->setType('Maintenance');
        $manager->persist($type);

        $type = new Type();
        $type->setType('Développement');
        $manager->persist($type);

        for ($i = 1; $i <= 30; $i++) {
            for ($j = 1; $j <= 42; $j++) {
                if ($j < 10) {
                    $unit = new Unit();
                    $unit->setReference('U0' . $j);
                    $unit->setBay($manager->getRepository(Bay::class)->findOneBy(['reference' => 'B00' . $i]));
                    $unit->setState($manager->getRepository(Usage::class)->findOneBy(['type' => 'Site Web']));
                    $manager->persist($unit);
                } else {
                    $unit = new Unit();
                    $unit->setReference('U' . $j);
                    $unit->setBay($manager->getRepository(Bay::class)->findOneBy(['reference' => 'B0' . $i]));
                    $unit->setState($manager->getRepository(Usage::class)->findOneBy(['type' => 'Application Mobile']));
                    $manager->persist($unit);
                }
            }
        }

        $intervention = new Intervention();
        $intervention->setTitle('Intervention 1');
        $intervention->setDescription('Description de l\'intervention 1');
        $intervention->setType($manager->getRepository(Type::class)->findOneBy(['type' => 'Maintenance']));

        $manager->flush();
    }
}

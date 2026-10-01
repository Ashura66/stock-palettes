<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }
    public function load(ObjectManager $manager): void
    {
        $categories = [];
        foreach (['Fruits', 'Légumes', 'Emballages'] as $name) {
            $category = (new Category())->setName($name);
            $manager->persist($category);
            $categories[$name] = $category;
        }

        $products = [
            // sku, nom, unité, colis par palette, stock minimum, stock actuel, catégorie
            ['FRU-001', 'Pommes Golden', 'colis', 48, 20, 120, 'Fruits'],
            ['FRU-002', 'Poires Conférence', 'colis', 42, 20, 12, 'Fruits'],
            ['LEG-001', 'Tomates grappe', 'colis', 60, 30, 200, 'Légumes'],
            ['LEG-002', 'Courgettes', 'colis', 54, 25, 8, 'Légumes'],
            ['EMB-001', 'Cartons 5 kg', 'unité', 500, 200, 1500, 'Emballages'],
        ];

        foreach ($products as [$sku, $name, $unit, $perPallet, $min, $stock, $cat]) {
            $product = (new Product())
                ->setSku($sku)
                ->setName($name)
                ->setUnit($unit)
                ->setParcelsPerPallet($perPallet)
                ->setMinStock($min)
                ->setCurrentStock($stock)
                ->setIsActive(true)
                ->setCategory($categories[$cat]);
            $manager->persist($product);
        }

        $users = [
            ['admin@stock.test', 'Admin', ['ROLE_ADMIN'], 'admin1234'],
            ['operateur@stock.test', 'Opérateur', ['ROLE_OPERATOR'], 'operateur1234'],
        ];
        foreach ($users as [$email, $name, $roles, $plain]) {
            $user = (new User())
                ->setEmail($email)
                ->setName($name)
                ->setRoles($roles);
            $user->setPassword($this->hasher->hashPassword($user, $plain));
            $manager->persist($user);
        }

        $manager->flush();
    }
}

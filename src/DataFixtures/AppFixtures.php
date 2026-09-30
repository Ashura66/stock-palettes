<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
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

        $manager->flush();
    }
}

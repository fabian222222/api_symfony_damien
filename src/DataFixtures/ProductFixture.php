<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Product;
use App\Entity\Category;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $products = [
            [
                "name" => 'Nike',
                "quantity" => rand(1,1000),
                "price" => rand(1, 100),
                "shortDescription" => 'This is my short description from fixtures',
                "description" => 'This is my description from fixtures'
            ],
            [
                "name" => 'Adidas',
                "quantity" => rand(1,1000),
                "price" => rand(1, 100),
                "shortDescription" => 'This is my short description from fixtures',
                "description" => 'This is my description from fixtures'
            ],
            [
                "name" => 'Lacoste',
                "quantity" => rand(1,1000),
                "price" => rand(1, 100),
                "shortDescription" => 'This is my short description from fixtures',
                "description" => 'This is my description from fixtures'
            ],
            [
                "name" => 'Channel',
                "quantity" => rand(1,1000),
                "price" => rand(1, 100),
                "shortDescription" => 'This is my short description from fixtures',
                "description" => 'This is my description from fixtures'
            ],
            [
                "name" => 'Uniqlo',
                "quantity" => rand(1,1000),
                "price" => rand(1, 100),
                "shortDescription" => 'This is my short description from fixtures',
                "description" => 'This is my description from fixtures'
            ]
        ];
        
        foreach ($products as $product) {
            $newProduct = new Product();
            $newProduct->setName($product['name']);
            $newProduct->setQuantity($product['quantity']);
            $newProduct->setPrice($product['price']);
            $newProduct->setShortDescription($product['shortDescription']);
            $newProduct->setDescription($product['description']);
            $newProduct->addCategory($this->getReference('category_' . rand(1,4)));
            $manager->persist($newProduct);
        }

        $manager->flush();
    }
}

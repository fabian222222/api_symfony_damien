<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Category;

class CategoryFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categories = [
            'shirt',
            'polo',
            'pant',
            'socks',
            'hoodie'
        ];
        
        foreach ($categories as $index => $category) {
            $newCategory = new Category();
            $newCategory->setName($category);
            $manager->persist($newCategory);
            $this->addReference('category_' . $index, $newCategory);
        }

        $manager->flush();
    }
}

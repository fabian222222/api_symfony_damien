<?php

namespace App\Tests\Controller\Testts;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use App\Entity\Client;
use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\Category;
use DateTimeImmutable;

class CartValidationTest extends WebTestCase
{
    private $em;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
    
    public function testSomething(
    ): void
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
        
        $kernel = self::bootKernel();
        $em = $kernel->getContainer()->get('doctrine')->getManager();

        $newCategory = new Category();
        $newCategory->setName('testCategory');
        $categoryRepo = $this->em->getRepository(Category::class);
        $categoryRepo->save($newCategory, true);

        $client = new Client();
        $client->setFirstName('test');
        $client->setLastName('test');
        $client->setEmail('test@gmail.com');
        $client->setBirthDate(new DateTimeImmutable('2002-02-02'));
        $clientRepo = $this->em->getRepository(Client::class);
        $clientRepo->save($client, true);

        $cart = new Cart();
        $cart->setClient($client);
        $cart->setCreatedAt(new DateTimeImmutable());
        $cart->setTotalAmount(0);

        foreach ($products as $product) {
            $newProduct = new Product();
            $newProduct->setName($product['name']);
            $newProduct->setQuantity($product['quantity']);
            $newProduct->setPrice($product['price']);
            $newProduct->setShortDescription($product['shortDescription']);
            $newProduct->setDescription($product['description']);
            $newProduct->addCategory($newCategory);
            $cart->addProduct($newProduct);
        }
        $cartRepo = $this->em->getRepository(Cart::class);
        $cartRepo->save($cart, true);

        $cartId = $cart->getId();
        $webUri = '/api/carts/' . $cartId . '/validate';

        $webClient = self::createClient();
        $webClient->request('PATCH', $webUri);

        $this->assertTrue($webClient->getResponse()->isSuccessful());
    }

}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\OrderEntry;
use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use DateTimeImmutable;

class CartController extends AbstractController
{
    #[Route('/api/carts/{cart_id}/products/{product_id}/add-to-cart', name: 'add_product_to_cart', methods: ['PATCH'])]
    public function add_product_to_cart(
        int $cart_id,
        int $product_id,
        CartRepository $cartRepo,
        ProductRepository $productRepo,
        SerializerInterface $serializer
    ): Response
    {   
        $cart = $cartRepo->find($cart_id);
        $product = $productRepo->find($product_id);

        if ($product && $cart) {
            $cart->addProduct($product);
            $cart->setTotalAmount($cart->getTotalAmount() + $product->getPrice());
            $product->setQuantity($product->getQuantity() - 1);

            $cartRepo->save($cart, true);
            $productRepo->save($product, true);

            $jsonCart = json_decode($serializer->serialize($cart, 'json', ['groups' => 'show_cart']));
            $jsonProduct = json_decode($serializer->serialize($product, 'json', ['groups' => 'show_product']));

            return $this->json([
                'cart' => $jsonCart,
                'product' => $jsonProduct,
                'message' => 'Cart is now updated'
            ]);
        }

        return $this->json([
            'errorMessage' => 'Cart or product doest not exist'
        ]);
    }

    #[Route('/api/carts/{cart_id}/products/{product_id}/remove-to-cart', name: 'remove_product_to_cart', methods: ['DELETE'])]
    public function remove_product_to_cart(
        int $cart_id,
        int $product_id,
        CartRepository $cartRepo,
        ProductRepository $productRepo,
        SerializerInterface $serializer
    ): Response
    {   
        $cart = $cartRepo->find($cart_id);
        $product = $productRepo->find($product_id);

        if ($product && $cart) {
            $cart->removeProduct($product);
            $cart->setTotalAmount($cart->getTotalAmount() - $product->getPrice());

            $cartRepo->save($cart, true);

            $jsonCart = json_decode($serializer->serialize($cart, 'json', ['groups' => 'show_cart']));
            $jsonProduct = json_decode($serializer->serialize($product, 'json', ['groups' => 'show_product']));

            return $this->json([
                'cart' => $jsonCart,
                'product' => $jsonProduct,
                'message' => 'Cart is now updated'
            ]);
        }

        return $this->json([
            'errorMessage' => 'Cart or product doest not exist'
        ]);
    }

    #[Route('/api/carts/{id}', name: 'delete_cart', methods: ['DELETE'])]
    public function delete_cart(
        Cart $cart,
        CartRepository $cartRepo,
        SerializerInterface $serializer
    ): Response
    {   

        $products = $cart->getProducts();
        foreach ($products as $product) {
            $cart->removeProduct($product);
        }
        $cartRepo->save($cart, true);

        $jsonCart = json_decode($serializer->serialize($cart, 'json', ['groups' => 'show_cart']));
        return $this->json($jsonCart);
    }

    #[Route('/api/carts/{id}/validate', name: 'validate_cart', methods: ['PATCH'])]
    public function validate_cart(
        Cart $cart,
        CartRepository $cartRepo,
        OrderRepository $orderRepo,
        SerializerInterface $serializer
    ): Response
    {   
        $order = new Order();
        $order->setClient($cart->getClient());
        $order->setCreatedAt(new DateTimeImmutable());
        $order->setPayementMethod(1);
        $order->setCode('ABCDEFGHIJKLMNOPQRSTUVWXZYZ');

        $client = $cart->getClient();
        $clientAddress = $client->getAddresses()[0]->getStreet() . $client->getAddresses()[0]->getPostalCode() . $client->getAddresses()[0]->getCity();

        $order->setAddressUsed($clientAddress);

        $products = $cart->getProducts();
        $orderTotalPrice = 0;
        foreach ($products as $product) {
            $orderTotalPrice += $product->getPrice();

            $orderEntry = new OrderEntry();
            $orderEntry->setName($product->getName());
            $orderEntry->setName(1);
            $orderEntry->setPrice($product->getPrice());
            $orderEntry->setShortDescription($product->getShortDescription());
            $orderEntry->setDescription($product->getDescription());
            $orderEntry->setCreatedAt(new DateTimeImmutable());

            $order->addOrderEntry($orderEntry);
            
            $cart->removeProduct($product);
        }

        $order->setTotal($orderTotalPrice);

        $orderRepo->save($order, true);
        $cartRepo->save($cart, true);

        $jsonCart = json_decode($serializer->serialize($cart, 'json', ['groups' => 'show_cart']));
        $jsonOrder = json_decode($serializer->serialize($order, 'json', ['groups' => 'show_client_order']));
        return $this->json([
            "cart" => $jsonCart,
            "order" => $jsonOrder,
        ]);
    }
}

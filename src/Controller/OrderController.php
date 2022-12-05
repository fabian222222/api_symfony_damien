<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

use App\Repository\OrderRepository;
use App\Entity\Order;

class OrderController extends AbstractController
{
    #[Route('/api/orders', name: 'get_user_order', methods: ['GET'])]
    public function get_user_order(
        Request $request,
        OrderRepository $orderRepo,
        SerializerInterface $serializer
    ): Response
    {
        $clientId = $request->query->get('client_id');
        $orders = $orderRepo->findByClient($clientId);
        
        $json = json_decode($serializer->serialize($orders, 'json', ['groups' => 'show_client_order']));

        return $this->json($json);
    }


    #[Route('/api/orders/{id}', name: 'get_order', methods: ['GET'])]
    public function get_order(
        Order $order,
        SerializerInterface $serializer
    ): Response
    {
        $json = json_decode($serializer->serialize($order, 'json', ['groups' => 'show_client_order_detail']));
        return $this->json($json);
    }
}

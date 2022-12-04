<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Form\ClientType;

class ClientController extends AbstractController
{
    #[Route('/api/client', name: 'get_clients', methods: ['GET'])]
    public function get_clients(
        ClientRepository $clientRepo
    ): Response
    {
        $clients = $clientRepo->findAll();
        return $this->json($clients);
    }

    #[Route('/api/client/{id}', name: 'get_client', methods: ['GET'])]
    public function get_client(
        ClientRepository $clientRepo,
        int $id
    ): Response
    {
        $client = $clientRepo->find($id);

        if ($client) {
            return $this->json($client);
        } else {
            return $this->json([
                'error' => 'This client does not exist'
            ]);
        }
    }

    #[Route('/api/client', name: 'create_client', methods: ['POST'])]
    public function create_client(
        Request $request,
        ClientRepository $clientRepo
    ): Response
    {

        $client = new Client();

        $form = $this->createForm(
            ClientType::class,
            $client,
            ['method' => 'POST']
        );

        $data = json_decode($request->getContent(), true);

        $form->handleRequest($request);
        $form->submit($data);

        if (!$form->isValid()) {
            return $this->json([
                'error' => 'Data is not good'
            ]);
        }

        $clientRepo->save($client, true);

        return $this->json($client);
    }

    #[Route('/api/client/{id}', name: 'app_client', methods: ['PUT'])]
    public function update_client(
        Request $request,
        ClientRepository $clientRepo,
        Client $client
    ): Response
    {
        $form = $this->createForm(
            ClientType::class,
            $client,
            ['method' => 'PUT']
        );

        $data = json_decode($request->getContent(), true);

        $form->handleRequest($request);
        $form->submit($data);

        if (!$form->isValid()) {
            return $this->json([
                'error' => 'Data is not good'
            ]);
        }

        $clientRepo->save($client, true);

        return $this->json($client);
    }

    #[Route('/api/client/{id}', name: 'remove_client', methods: ['DELETE'])]
    public function delete_client(
        Client $client,
        ClientRepository $clientRepo
    ): Response
    {
        $clientRepo->remove($client, true);
        return $this->json($client);
    }
}

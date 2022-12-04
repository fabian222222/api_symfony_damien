<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Form\ProductType;

class ProductController extends AbstractController
{
    #[Route('/api/product', name: 'get_products', methods: ['GET'])]
    public function get_products(
        ProductRepository $productRepo
    ): Response
    {
        $products = $productRepo->findAll();
        return $this->json($products);
    }

    #[Route('/api/product/{id}', name: 'get_product', methods: ['GET'])]
    public function get_product(
        ProductRepository $productRepo,
        int $id
    ): Response
    {
        $product = $productRepo->find($id);

        if ($product) {
            return $this->json($product);
        } else {
            return $this->json([
                'error' => 'This product does not exist'
            ]);
        }
    }

    #[Route('/api/product', name: 'create_product', methods: ['POST'])]
    public function create_product(
        Request $request,
        ProductRepository $productRepo
    ): Response
    {

        $product = new Product();

        $form = $this->createForm(
            productType::class,
            $product,
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

        $productRepo->save($product, true);

        return $this->json($product);
    }

    #[Route('/api/product/{id}', name: 'app_product', methods: ['PUT'])]
    public function update_product(
        Request $request,
        ProductRepository $productRepo,
        Product $product
    ): Response
    {
        $form = $this->createForm(
            productType::class,
            $product,
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

        $productRepo->save($product, true);

        return $this->json($product);
    }

    #[Route('/api/product/{id}', name: 'remove_product', methods: ['DELETE'])]
    public function delete_product(
        Product $product,
        ProductRepository $productRepo
    ): Response
    {
        $productRepo->remove($product, true);
        return $this->json($product);
    }
}

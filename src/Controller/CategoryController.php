<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Form\CategoryType;

class CategoryController extends AbstractController
{
    #[Route('/api/category', name: 'get_categorys', methods: ['GET'])]
    public function get_categorys(
        CategoryRepository $CategoryRepo
    ): Response
    {
        $categories = $CategoryRepo->findAll();
        return $this->json($categories);
    }

    #[Route('/api/category/{id}', name: 'get_category', methods: ['GET'])]
    public function get_category(
        CategoryRepository $CategoryRepo,
        int $id
    ): Response
    {
        $category = $CategoryRepo->find($id);

        if ($category) {
            return $this->json($category);
        } else {
            return $this->json([
                'error' => 'This category does not exist'
            ]);
        }
    }

    #[Route('/api/category', name: 'create_category', methods: ['POST'])]
    public function create_category(
        Request $request,
        CategoryRepository $CategoryRepo
    ): Response
    {

        $category = new Category();

        $form = $this->createForm(
            categoryType::class,
            $category,
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

        $CategoryRepo->save($category, true);

        return $this->json($category);
    }

    #[Route('/api/category/{id}', name: 'app_category', methods: ['PUT'])]
    public function update_category(
        Request $request,
        CategoryRepository $CategoryRepo,
        Category $category
    ): Response
    {
        $form = $this->createForm(
            categoryType::class,
            $category,
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

        $CategoryRepo->save($category, true);

        return $this->json($category);
    }

    #[Route('/api/category/{id}', name: 'remove_category', methods: ['DELETE'])]
    public function delete_category(
        Category $category,
        CategoryRepository $CategoryRepo
    ): Response
    {
        $CategoryRepo->remove($category, true);
        return $this->json($category);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\ProductTable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ProductTableController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/ProductTable/product', name: 'app_ProductTable_product', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $ProductTables = $this->getDoctrine()->getRepository(ProductTable::class)->findAll();

        $data = [];

        foreach ($ProductTables as $ProductTable) {
            $data = [
                'id' => $ProductTable->getId(),
                'name' => $ProductTable->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/ProductTable/product/store', name: 'store_ProductTable_product', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $ProductTable = new ProductTable();

        $ProductTable->setName($request->request->get('name'));

        $this->em->persist($ProductTable);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/ProductTable/product/{id}', name: 'show_ProductTable_product',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $ProductTable = $this->getDoctrine()->getRepository(ProductTable::class)->find($id);

        if (!$ProductTable) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $ProductTable->getId(),
            'name' => $ProductTable->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/ProductTable/product/edit/{id}', name: 'update_ProductTable_product',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $ProductTable = $entityManager->getRepository(ProductTable::class)->find($id);

        if (!$ProductTable) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $ProductTable->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/ProductTable/product/destroy/{id}', name: 'destroy_ProductTable_product',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $ProductTable = $entityManager->getRepository(ProductTable::class)->find($id);

        if (!$ProductTable) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($ProductTable);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\CategoryCompte;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class CategoryCompteController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/CategoryCompte/product', name: 'app_CategoryCompte_product', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $CategoryComptes = $this->getDoctrine()->getRepository(CategoryCompte::class)->findAll();

        $data = [];

        foreach ($CategoryComptes as $CategoryCompte) {
            $data = [
                'id' => $CategoryCompte->getId(),
                'name' => $CategoryCompte->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/CategoryCompte/product/store', name: 'store_CategoryCompte_product', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $CategoryCompte = new CategoryCompte();

        $CategoryCompte->setName($request->request->get('name'));

        $this->em->persist($CategoryCompte);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/CategoryCompte/product/{id}', name: 'show_CategoryCompte_product',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $CategoryCompte = $this->getDoctrine()->getRepository(CategoryCompte::class)->find($id);

        if (!$CategoryCompte) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $CategoryCompte->getId(),
            'name' => $CategoryCompte->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/CategoryCompte/product/edit/{id}', name: 'update_CategoryCompte_product',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $CategoryCompte = $entityManager->getRepository(CategoryCompte::class)->find($id);

        if (!$CategoryCompte) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $CategoryCompte->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/CategoryCompte/product/destroy/{id}', name: 'destroy_CategoryCompte_product',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $CategoryCompte = $entityManager->getRepository(CategoryCompte::class)->find($id);

        if (!$CategoryCompte) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($CategoryCompte);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

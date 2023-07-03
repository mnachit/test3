<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\TypeCompte;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class TypeCompteController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/TypeCompte/product', name: 'app_TypeCompte_product', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $TypeComptes = $this->getDoctrine()->getRepository(TypeCompte::class)->findAll();

        $data = [];

        foreach ($TypeComptes as $TypeCompte) {
            $data = [
                'id' => $TypeCompte->getId(),
                'name' => $TypeCompte->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/TypeCompte/product/store', name: 'store_TypeCompte_product', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $TypeCompte = new TypeCompte();

        $TypeCompte->setName($request->request->get('name'));

        $this->em->persist($TypeCompte);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/TypeCompte/product/{id}', name: 'show_TypeCompte_product',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $TypeCompte = $this->getDoctrine()->getRepository(TypeCompte::class)->find($id);

        if (!$TypeCompte) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $TypeCompte->getId(),
            'name' => $TypeCompte->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/TypeCompte/product/edit/{id}', name: 'update_TypeCompte_product',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $TypeCompte = $entityManager->getRepository(TypeCompte::class)->find($id);

        if (!$TypeCompte) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $TypeCompte->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/TypeCompte/product/destroy/{id}', name: 'destroy_TypeCompte_product',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $TypeCompte = $entityManager->getRepository(TypeCompte::class)->find($id);

        if (!$TypeCompte) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($TypeCompte);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

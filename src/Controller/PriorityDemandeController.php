<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\PriorityDemande;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class PriorityDemandeController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/PriorityDemande/product', name: 'app_PriorityDemande_product', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $PriorityDemandes = $this->getDoctrine()->getRepository(PriorityDemande::class)->findAll();

        $data = [];

        foreach ($PriorityDemandes as $PriorityDemande) {
            $data = [
                'id' => $PriorityDemande->getId(),
                'name' => $PriorityDemande->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/PriorityDemande/product/store', name: 'store_PriorityDemande_product', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $PriorityDemande = new PriorityDemande();

        $PriorityDemande->setName($request->request->get('name'));

        $this->em->persist($PriorityDemande);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/PriorityDemande/product/{id}', name: 'show_PriorityDemande_product',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $PriorityDemande = $this->getDoctrine()->getRepository(PriorityDemande::class)->find($id);

        if (!$PriorityDemande) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $PriorityDemande->getId(),
            'name' => $PriorityDemande->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/PriorityDemande/product/edit/{id}', name: 'update_PriorityDemande_product',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $PriorityDemande = $entityManager->getRepository(PriorityDemande::class)->find($id);

        if (!$PriorityDemande) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $PriorityDemande->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/PriorityDemande/product/destroy/{id}', name: 'destroy_PriorityDemande_product',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $PriorityDemande = $entityManager->getRepository(PriorityDemande::class)->find($id);

        if (!$PriorityDemande) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($PriorityDemande);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\RaisonDemande;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class RaisonDemandeController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/RaisonDemande/product', name: 'app_RaisonDemande_product', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $RaisonDemandes = $this->getDoctrine()->getRepository(RaisonDemande::class)->findAll();

        $data = [];

        foreach ($RaisonDemandes as $RaisonDemande) {
            $data = [
                'id' => $RaisonDemande->getId(),
                'name' => $RaisonDemande->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/RaisonDemande/product/store', name: 'store_RaisonDemande_product', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $RaisonDemande = new RaisonDemande();

        $RaisonDemande->setName($request->request->get('name'));

        $this->em->persist($RaisonDemande);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/RaisonDemande/product/{id}', name: 'show_RaisonDemande_product',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $RaisonDemande = $this->getDoctrine()->getRepository(RaisonDemande::class)->find($id);

        if (!$RaisonDemande) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $RaisonDemande->getId(),
            'name' => $RaisonDemande->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/RaisonDemande/product/edit/{id}', name: 'update_RaisonDemande_product',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $RaisonDemande = $entityManager->getRepository(RaisonDemande::class)->find($id);

        if (!$RaisonDemande) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $RaisonDemande->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/RaisonDemande/product/destroy/{id}', name: 'destroy_RaisonDemande_product',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $RaisonDemande = $entityManager->getRepository(RaisonDemande::class)->find($id);

        if (!$RaisonDemande) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($RaisonDemande);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

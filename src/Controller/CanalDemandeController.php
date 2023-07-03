<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\CanalDemande;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class CanalDemandeController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/CanalDemande/product', name: 'app_CanalDemande_product', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $CanalDemandes = $this->getDoctrine()->getRepository(CanalDemande::class)->findAll();

        $data = [];

        foreach ($CanalDemandes as $CanalDemande) {
            $data = [
                'id' => $CanalDemande->getId(),
                'name' => $CanalDemande->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/CanalDemande/product/store', name: 'store_CanalDemande_product', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $CanalDemande = new CanalDemande();

        $CanalDemande->setName($request->request->get('name'));

        $this->em->persist($CanalDemande);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/CanalDemande/product/{id}', name: 'show_CanalDemande_product',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $CanalDemande = $this->getDoctrine()->getRepository(CanalDemande::class)->find($id);

        if (!$CanalDemande) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $CanalDemande->getId(),
            'name' => $CanalDemande->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/CanalDemande/product/edit/{id}', name: 'update_CanalDemande_product',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $CanalDemande = $entityManager->getRepository(CanalDemande::class)->find($id);

        if (!$CanalDemande) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $CanalDemande->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/CanalDemande/product/destroy/{id}', name: 'destroy_CanalDemande_product',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $CanalDemande = $entityManager->getRepository(CanalDemande::class)->find($id);

        if (!$CanalDemande) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($CanalDemande);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\TaxeVente;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class TaxeVenteController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/TaxeVente/product', name: 'app_TaxeVente_product', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $TaxeVentes = $this->getDoctrine()->getRepository(TaxeVente::class)->findAll();

        $data = [];

        foreach ($TaxeVentes as $TaxeVente) {
            $data = [
                'id' => $TaxeVente->getId(),
                'name' => $TaxeVente->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/TaxeVente/product/store', name: 'store_TaxeVente_product', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $TaxeVente = new TaxeVente();

        $TaxeVente->setName($request->request->get('name'));

        $this->em->persist($TaxeVente);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/TaxeVente/product/{id}', name: 'show_TaxeVente_product',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $TaxeVente = $this->getDoctrine()->getRepository(TaxeVente::class)->find($id);

        if (!$TaxeVente) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $TaxeVente->getId(),
            'name' => $TaxeVente->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/TaxeVente/product/edit/{id}', name: 'update_TaxeVente_product',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $TaxeVente = $entityManager->getRepository(TaxeVente::class)->find($id);

        if (!$TaxeVente) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $TaxeVente->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/TaxeVente/product/destroy/{id}', name: 'destroy_TaxeVente_product',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $TaxeVente = $entityManager->getRepository(TaxeVente::class)->find($id);

        if (!$TaxeVente) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($TaxeVente);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\TaxeAchat;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class TaxeAchatController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/TaxeAchat/product', name: 'app_TaxeAchat_product', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $TaxeAchats = $this->getDoctrine()->getRepository(TaxeAchat::class)->findAll();

        $data = [];

        foreach ($TaxeAchats as $TaxeAchat) {
            $data = [
                'id' => $TaxeAchat->getId(),
                'name' => $TaxeAchat->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/TaxeAchat/product/store', name: 'store_TaxeAchat_product', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $TaxeAchat = new TaxeAchat();

        $TaxeAchat->setName($request->request->get('name'));

        $this->em->persist($TaxeAchat);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/TaxeAchat/product/{id}', name: 'show_TaxeAchat_product',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $TaxeAchat = $this->getDoctrine()->getRepository(TaxeAchat::class)->find($id);

        if (!$TaxeAchat) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $TaxeAchat->getId(),
            'name' => $TaxeAchat->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/TaxeAchat/product/edit/{id}', name: 'update_TaxeAchat_product',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $TaxeAchat = $entityManager->getRepository(TaxeAchat::class)->find($id);

        if (!$TaxeAchat) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $TaxeAchat->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/TaxeAchat/product/destroy/{id}', name: 'destroy_TaxeAchat_product',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $TaxeAchat = $entityManager->getRepository(TaxeAchat::class)->find($id);

        if (!$TaxeAchat) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($TaxeAchat);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

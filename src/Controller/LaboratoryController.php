<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Laboratory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class LaboratoryController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/Laboratory/product', name: 'app_Laboratory_product', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $Laboratorys = $this->getDoctrine()->getRepository(Laboratory::class)->findAll();

        $data = [];

        foreach ($Laboratorys as $Laboratory) {
            $data = [
                'id' => $Laboratory->getId(),
                'name' => $Laboratory->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/Laboratory/product/store', name: 'store_Laboratory_product', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $Laboratory = new Laboratory();

        $Laboratory->setName($request->request->get('name'));

        $this->em->persist($Laboratory);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/Laboratory/product/{id}', name: 'show_Laboratory_product',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $Laboratory = $this->getDoctrine()->getRepository(Laboratory::class)->find($id);

        if (!$Laboratory) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $Laboratory->getId(),
            'name' => $Laboratory->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/Laboratory/product/edit/{id}', name: 'update_Laboratory_product',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Laboratory = $entityManager->getRepository(Laboratory::class)->find($id);

        if (!$Laboratory) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $Laboratory->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/Laboratory/product/destroy/{id}', name: 'destroy_Laboratory_product',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Laboratory = $entityManager->getRepository(Laboratory::class)->find($id);

        if (!$Laboratory) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($Laboratory);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Ciblage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class CiblageController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/Ciblage', name: 'app_Ciblage', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $Ciblages = $this->getDoctrine()->getRepository(Ciblage::class)->findAll();

        $data = [];

        foreach ($Ciblages as $Ciblage) {
            $data = [
                'id' => $Ciblage->getId(),
                'name' => $Ciblage->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/Ciblage/store', name: 'store_Ciblage', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $Ciblage = new Ciblage();

        $Ciblage->setName($request->request->get('name'));

        $this->em->persist($Ciblage);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/Ciblage/{id}', name: 'show_Ciblage',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $Ciblage = $this->getDoctrine()->getRepository(Ciblage::class)->find($id);

        if (!$Ciblage) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $Ciblage->getId(),
            'name' => $Ciblage->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/Ciblage/edit/{id}', name: 'update_Ciblage',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Ciblage = $entityManager->getRepository(Ciblage::class)->find($id);

        if (!$Ciblage) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $Ciblage->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/Ciblage/destroy/{id}', name: 'destroy_Ciblage',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Ciblage = $entityManager->getRepository(Ciblage::class)->find($id);

        if (!$Ciblage) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($Ciblage);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

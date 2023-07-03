<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\TypeEstablishments;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class TypeEstablishmentsController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/TypeEstablishments/product', name: 'app_TypeEstablishments_product', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $TypeEstablishmentss = $this->getDoctrine()->getRepository(TypeEstablishments::class)->findAll();

        $data = [];

        foreach ($TypeEstablishmentss as $TypeEstablishments) {
            $data = [
                'id' => $TypeEstablishments->getId(),
                'name' => $TypeEstablishments->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/TypeEstablishments/product/store', name: 'store_TypeEstablishments_product', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $TypeEstablishments = new TypeEstablishments();

        $TypeEstablishments->setName($request->request->get('name'));

        $this->em->persist($TypeEstablishments);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/TypeEstablishments/product/{id}', name: 'show_TypeEstablishments_product',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $TypeEstablishments = $this->getDoctrine()->getRepository(TypeEstablishments::class)->find($id);

        if (!$TypeEstablishments) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $TypeEstablishments->getId(),
            'name' => $TypeEstablishments->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/TypeEstablishments/product/edit/{id}', name: 'update_TypeEstablishments_product',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $TypeEstablishments = $entityManager->getRepository(TypeEstablishments::class)->find($id);

        if (!$TypeEstablishments) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $TypeEstablishments->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/TypeEstablishments/product/destroy/{id}', name: 'destroy_TypeEstablishments_product',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $TypeEstablishments = $entityManager->getRepository(TypeEstablishments::class)->find($id);

        if (!$TypeEstablishments) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($TypeEstablishments);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

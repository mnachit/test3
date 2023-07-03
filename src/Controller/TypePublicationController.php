<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\TypePublication;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class TypePublicationController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/TypePublication', name: 'app_TypePublication', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $TypePublications = $this->getDoctrine()->getRepository(TypePublication::class)->findAll();

        $data = [];

        foreach ($TypePublications as $TypePublication) {
            $data = [
                'id' => $TypePublication->getId(),
                'name' => $TypePublication->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/TypePublication/store', name: 'store_TypePublication', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $TypePublication = new TypePublication();

        $TypePublication->setName($request->request->get('name'));

        $this->em->persist($TypePublication);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/TypePublication/{id}', name: 'show_TypePublication',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $TypePublication = $this->getDoctrine()->getRepository(TypePublication::class)->find($id);

        if (!$TypePublication) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $TypePublication->getId(),
            'name' => $TypePublication->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/TypePublication/edit/{id}', name: 'update_TypePublication',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $TypePublication = $entityManager->getRepository(TypePublication::class)->find($id);

        if (!$TypePublication) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $TypePublication->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/TypePublication/destroy/{id}', name: 'destroy_TypePublication',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $TypePublication = $entityManager->getRepository(TypePublication::class)->find($id);

        if (!$TypePublication) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($TypePublication);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

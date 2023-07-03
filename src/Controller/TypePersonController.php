<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\TypePerson;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class TypePersonController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/TypePerson/product', name: 'app_TypePerson_product', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $TypePersons = $this->getDoctrine()->getRepository(TypePerson::class)->findAll();

        $data = [];

        foreach ($TypePersons as $TypePerson) {
            $data = [
                'id' => $TypePerson->getId(),
                'name' => $TypePerson->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/TypePerson/product/store', name: 'store_TypePerson_product', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $TypePerson = new TypePerson();

        $TypePerson->setName($request->request->get('name'));

        $this->em->persist($TypePerson);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/TypePerson/product/{id}', name: 'show_TypePerson_product',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $TypePerson = $this->getDoctrine()->getRepository(TypePerson::class)->find($id);

        if (!$TypePerson) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $TypePerson->getId(),
            'name' => $TypePerson->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/TypePerson/product/edit/{id}', name: 'update_TypePerson_product',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $TypePerson = $entityManager->getRepository(TypePerson::class)->find($id);

        if (!$TypePerson) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $TypePerson->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/TypePerson/product/destroy/{id}', name: 'destroy_TypePerson_product',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $TypePerson = $entityManager->getRepository(TypePerson::class)->find($id);

        if (!$TypePerson) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($TypePerson);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

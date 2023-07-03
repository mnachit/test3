<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\ClasseTherapeutique;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ClasseTherapeutiqueController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/ClasseTherapeutique/product', name: 'app_ClasseTherapeutique_product', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $ClasseTherapeutiques = $this->getDoctrine()->getRepository(ClasseTherapeutique::class)->findAll();

        $data = [];

        foreach ($ClasseTherapeutiques as $ClasseTherapeutique) {
            $data = [
                'id' => $ClasseTherapeutique->getId(),
                'name' => $ClasseTherapeutique->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/ClasseTherapeutique/product/store', name: 'store_ClasseTherapeutique_product', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $ClasseTherapeutique = new ClasseTherapeutique();

        $ClasseTherapeutique->setName($request->request->get('name'));

        $this->em->persist($ClasseTherapeutique);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/ClasseTherapeutique/product/{id}', name: 'show_ClasseTherapeutique_product',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $ClasseTherapeutique = $this->getDoctrine()->getRepository(ClasseTherapeutique::class)->find($id);

        if (!$ClasseTherapeutique) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $ClasseTherapeutique->getId(),
            'name' => $ClasseTherapeutique->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/ClasseTherapeutique/product/edit/{id}', name: 'update_ClasseTherapeutique_product',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $ClasseTherapeutique = $entityManager->getRepository(ClasseTherapeutique::class)->find($id);

        if (!$ClasseTherapeutique) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $ClasseTherapeutique->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/ClasseTherapeutique/product/destroy/{id}', name: 'destroy_ClasseTherapeutique_product',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $ClasseTherapeutique = $entityManager->getRepository(ClasseTherapeutique::class)->find($id);

        if (!$ClasseTherapeutique) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($ClasseTherapeutique);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

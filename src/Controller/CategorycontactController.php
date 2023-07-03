<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Categorycontacts;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class CategorycontactController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/Categorycontacts/product', name: 'app_Categorycontacts_product', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $Categorycontactss = $this->getDoctrine()->getRepository(Categorycontacts::class)->findAll();

        $data = [];

        foreach ($Categorycontactss as $Categorycontacts) {
            $data = [
                'id' => $Categorycontacts->getId(),
                'name' => $Categorycontacts->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/Categorycontacts/product/store', name: 'store_Categorycontacts_product', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $Categorycontacts = new Categorycontacts();

        $Categorycontacts->setName($request->request->get('name'));

        $this->em->persist($Categorycontacts);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/Categorycontacts/product/{id}', name: 'show_Categorycontacts_product',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $Categorycontacts = $this->getDoctrine()->getRepository(Categorycontacts::class)->find($id);

        if (!$Categorycontacts) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $Categorycontacts->getId(),
            'name' => $Categorycontacts->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/Categorycontacts/product/edit/{id}', name: 'update_Categorycontacts_product',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Categorycontacts = $entityManager->getRepository(Categorycontacts::class)->find($id);

        if (!$Categorycontacts) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $Categorycontacts->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/Categorycontacts/product/destroy/{id}', name: 'destroy_Categorycontacts_product',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Categorycontacts = $entityManager->getRepository(Categorycontacts::class)->find($id);

        if (!$Categorycontacts) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($Categorycontacts);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

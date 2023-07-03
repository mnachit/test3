<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Gamme;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class GammeController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/Gamme/product', name: 'app_Gamme_product', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $Gammes = $this->getDoctrine()->getRepository(Gamme::class)->findAll();

        $data = [];

        foreach ($Gammes as $Gamme) {
            $data = [
                'id' => $Gamme->getId(),
                'name' => $Gamme->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/Gamme/product/store', name: 'store_Gamme_product', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $Gamme = new Gamme();

        $Gamme->setName($request->request->get('name'));

        $this->em->persist($Gamme);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/Gamme/product/{id}', name: 'show_Gamme_product',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $Gamme = $this->getDoctrine()->getRepository(Gamme::class)->find($id);

        if (!$Gamme) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $Gamme->getId(),
            'name' => $Gamme->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/Gamme/product/edit/{id}', name: 'update_Gamme_product',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Gamme = $entityManager->getRepository(Gamme::class)->find($id);

        if (!$Gamme) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $Gamme->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/Gamme/product/destroy/{id}', name: 'destroy_Gamme_product',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Gamme = $entityManager->getRepository(Gamme::class)->find($id);

        if (!$Gamme) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($Gamme);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

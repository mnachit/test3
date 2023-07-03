<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Sousgame;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class SousgameController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/Sousgame/product', name: 'app_Sousgame_product', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $Sousgames = $this->getDoctrine()->getRepository(Sousgame::class)->findAll();

        $data = [];

        foreach ($Sousgames as $Sousgame) {
            $data = [
                'id' => $Sousgame->getId(),
                'name' => $Sousgame->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/Sousgame/product/store', name: 'store_Sousgame_product', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $Sousgame = new Sousgame();

        $Sousgame->setName($request->request->get('name'));

        $this->em->persist($Sousgame);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/Sousgame/product/{id}', name: 'show_Sousgame_product',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $Sousgame = $this->getDoctrine()->getRepository(Sousgame::class)->find($id);

        if (!$Sousgame) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $Sousgame->getId(),
            'name' => $Sousgame->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/Sousgame/product/edit/{id}', name: 'update_Sousgame_product',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Sousgame = $entityManager->getRepository(Sousgame::class)->find($id);

        if (!$Sousgame) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $Sousgame->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/Sousgame/product/destroy/{id}', name: 'destroy_Sousgame_product',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Sousgame = $entityManager->getRepository(Sousgame::class)->find($id);

        if (!$Sousgame) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($Sousgame);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

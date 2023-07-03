<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Pays;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class PaysController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/Pays', name: 'app_Pays', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $Payss = $this->getDoctrine()->getRepository(Pays::class)->findAll();

        $data = [];

        foreach ($Payss as $Pays) {
            $data = [
                'id' => $Pays->getId(),
                'name' => $Pays->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/Pays/store', name: 'store_Pays', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $Pays = new Pays();

        $Pays->setName($request->request->get('name'));

        $this->em->persist($Pays);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/Pays/{id}', name: 'show_Pays',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $Pays = $this->getDoctrine()->getRepository(Pays::class)->find($id);

        if (!$Pays) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $Pays->getId(),
            'name' => $Pays->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/Pays/edit/{id}', name: 'update_Pays',  methods: 'POST')]
    public function update(int $id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Pays = $entityManager->getRepository(Pays::class)->find($id);

        if (!$Pays) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $Pays->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/Pays/destroy/{id}', name: 'destroy_Pays',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Pays = $entityManager->getRepository(Pays::class)->find($id);

        if (!$Pays) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($Pays);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

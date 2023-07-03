<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Secteur;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class SecteurController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/Secteur', name: 'app_Secteur_', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $Secteurs = $this->getDoctrine()->getRepository(Secteur::class)->findAll();

        $data = [];

        foreach ($Secteurs as $Secteur) {
            $data = [
                'id' => $Secteur->getId(),
                'name' => $Secteur->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/Secteur/store', name: 'store_Secteur_', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $Secteur = new Secteur();

        $Secteur->setName($request->request->get('name'));

        $this->em->persist($Secteur);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/Secteur/{id}', name: 'show_Secteur_',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $Secteur = $this->getDoctrine()->getRepository(Secteur::class)->find($id);

        if (!$Secteur) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $Secteur->getId(),
            'name' => $Secteur->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/Secteur/edit/{id}', name: 'update_Secteur_',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Secteur = $entityManager->getRepository(Secteur::class)->find($id);

        if (!$Secteur) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $Secteur->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/Secteur/destroy/{id}', name: 'destroy_Secteur_',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Secteur = $entityManager->getRepository(Secteur::class)->find($id);

        if (!$Secteur) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($Secteur);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

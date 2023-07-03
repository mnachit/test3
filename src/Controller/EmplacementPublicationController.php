<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\EmplacementPublication;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class EmplacementPublicationController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/EmplacementPublication', name: 'app_EmplacementPublication', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $EmplacementPublications = $this->getDoctrine()->getRepository(EmplacementPublication::class)->findAll();

        $data = [];

        foreach ($EmplacementPublications as $EmplacementPublication) {
            $data = [
                'id' => $EmplacementPublication->getId(),
                'name' => $EmplacementPublication->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/EmplacementPublication/store', name: 'store_EmplacementPublication', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $EmplacementPublication = new EmplacementPublication();

        $EmplacementPublication->setName($request->request->get('name'));

        $this->em->persist($EmplacementPublication);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/EmplacementPublication/{id}', name: 'show_EmplacementPublication',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $EmplacementPublication = $this->getDoctrine()->getRepository(EmplacementPublication::class)->find($id);

        if (!$EmplacementPublication) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $EmplacementPublication->getId(),
            'name' => $EmplacementPublication->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/EmplacementPublication/edit/{id}', name: 'update_EmplacementPublication',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $EmplacementPublication = $entityManager->getRepository(EmplacementPublication::class)->find($id);

        if (!$EmplacementPublication) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $EmplacementPublication->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/EmplacementPublication/destroy/{id}', name: 'destroy_EmplacementPublication',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $EmplacementPublication = $entityManager->getRepository(EmplacementPublication::class)->find($id);

        if (!$EmplacementPublication) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($EmplacementPublication);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

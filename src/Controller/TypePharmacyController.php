<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\TypePharmacy;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class TypePharmacyController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/TypePharmacy', name: 'app_TypePharmacy', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $TypePharmacys = $this->getDoctrine()->getRepository(TypePharmacy::class)->findAll();

        $data = [];

        foreach ($TypePharmacys as $TypePharmacy) {
            $data = [
                'id' => $TypePharmacy->getId(),
                'name' => $TypePharmacy->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/TypePharmacy/store', name: 'store_TypePharmacy', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $TypePharmacy = new TypePharmacy();

        $TypePharmacy->setName($request->request->get('name'));

        $this->em->persist($TypePharmacy);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/TypePharmacy/{id}', name: 'show_TypePharmacy',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $TypePharmacy = $this->getDoctrine()->getRepository(TypePharmacy::class)->find($id);

        if (!$TypePharmacy) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $TypePharmacy->getId(),
            'name' => $TypePharmacy->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/TypePharmacy/edit/{id}', name: 'update_TypePharmacy',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $TypePharmacy = $entityManager->getRepository(TypePharmacy::class)->find($id);

        if (!$TypePharmacy) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $TypePharmacy->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/TypePharmacy/destroy/{id}', name: 'destroy_TypePharmacy',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $TypePharmacy = $entityManager->getRepository(TypePharmacy::class)->find($id);

        if (!$TypePharmacy) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($TypePharmacy);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\TargetedPeople;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class TargetedPeopleController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/TargetedPeople', name: 'app_TargetedPeople', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $TargetedPeoples = $this->getDoctrine()->getRepository(TargetedPeople::class)->findAll();

        $data = [];

        foreach ($TargetedPeoples as $TargetedPeople) {
            $data = [
                'id' => $TargetedPeople->getId(),
                'name' => $TargetedPeople->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/TargetedPeople/store', name: 'store_TargetedPeople', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $TargetedPeople = new TargetedPeople();

        $TargetedPeople->setName($request->request->get('name'));

        $this->em->persist($TargetedPeople);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/TargetedPeople/{id}', name: 'show_TargetedPeople',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $TargetedPeople = $this->getDoctrine()->getRepository(TargetedPeople::class)->find($id);

        if (!$TargetedPeople) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $TargetedPeople->getId(),
            'name' => $TargetedPeople->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/TargetedPeople/edit/{id}', name: 'update_TargetedPeople',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $TargetedPeople = $entityManager->getRepository(TargetedPeople::class)->find($id);

        if (!$TargetedPeople) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $TargetedPeople->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/TargetedPeople/destroy/{id}', name: 'destroy_TargetedPeople',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $TargetedPeople = $entityManager->getRepository(TargetedPeople::class)->find($id);

        if (!$TargetedPeople) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($TargetedPeople);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Dci;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class DciController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/Dci/product', name: 'app_Dci_product', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $Dcis = $this->getDoctrine()->getRepository(Dci::class)->findAll();

        $data = [];

        foreach ($Dcis as $Dci) {
            $data = [
                'id' => $Dci->getId(),
                'name' => $Dci->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/Dci/product/store', name: 'store_Dci_product', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $Dci = new Dci();

        $Dci->setName($request->request->get('name'));

        $this->em->persist($Dci);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/Dci/product/{id}', name: 'show_Dci_product',  methods: 'GET')]
    public function show($id): JsonResponse
    {
        $Dci = $this->getDoctrine()->getRepository(Dci::class)->find($id);

        if (!$Dci) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $Dci->getId(),
            'name' => $Dci->getName(),
        ];

        return $this->json($data);
    }

    #[Route('/Dci/product/edit/{id}', name: 'update_Dci_product',  methods: 'POST')]
    public function update($id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Dci = $entityManager->getRepository(Dci::class)->find($id);

        if (!$Dci) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $Dci->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    #[Route('/Dci/product/destroy/{id}', name: 'destroy_Dci_product',  methods: 'DELETE')]
    public function destroy($id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Dci = $entityManager->getRepository(Dci::class)->find($id);

        if (!$Dci) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($Dci);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

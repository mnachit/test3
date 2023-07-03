<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Ville;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/ville')]
class VilleController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'index_ville', methods: ["GET"])]
    public function index(): JsonResponse
    {

        // $Villes = $this->getDoctrine()->getRepository(Ville::class)->findAll();
        $villes = $this->em->getRepository(Ville::class)->findAll() ;

        $data = [];

        foreach ($villes as $ville) {
            $data = [
                'id' => $ville->getId(),
                'name' => $ville->getName(),
            ];
        }

        return new JsonResponse([
            "data" => $data
        ]) ;
    }

    #[Route('/store', name: 'store_ville', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $ville = new ville();

        $ville->setName($request->request->get('name'));

        $this->em->persist($ville);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    #[Route('/{id}', name: 'show_Ville',  methods: 'GET')]
    public function show(int $id): JsonResponse
    {
        // $Ville = $this->getDoctrine()->getRepository(Ville::class)->find($id);
        $ville = $this->em->getRepository(Ville::class)->find($id) ;

        if (!$ville) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $ville->getId(),
            'name' => $ville->getName(),
        ];

        return new JsonResponse($data) ;
    }

    #[Route('/update/{id}', name: 'update_Ville',  methods: 'POST')]
    public function update(int $id, Request $request): JsonResponse
    {
        // $entityManager = $this->getDoctrine()->getManager();
        // $Ville = $entityManager->getRepository(Ville::class)->find($id);

        $ville = $this->em->getRepository(Ville::class)->find($id);

        if (!$ville) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $ville->setName($name);

        $this->em->flush();

        return new JsonResponse(
            ['message' => "Updated successfully"]
        ) ;
    }

    #[Route('/destroy/{id}', name: 'destroy_Ville',  methods: 'DELETE')]
    public function destroy(int $id): JsonResponse
    {
        // $entityManager = $this->getDoctrine()->getManager();
        // $Ville = $entityManager->getRepository(Ville::class)->find($id);
        $ville = $this->em->getRepository(Ville::class)->find($id) ;


        if (!$ville) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $this->em->remove($ville);
        $this->em->flush();

        return new JsonResponse(['message' => "destroy successfully"]) ;
    }
}

<?php

namespace App\Controller;

use App\Entity\Associations;
use App\Entity\Establishments;
use App\Entity\Pharmacy;
use App\Entity\Publications;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

// #[Route('/api')]
class DashbordController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validateEmpty($test)
    {
        if (empty($test))
            $test = "--";

        return $test;
    }

    public function validateEntityById($entityClass, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entity = $entityManager->getRepository($entityClass)->findOneBy(['id' => $id]);

        if (!$entity) {
            return new JsonResponse(['error' => "Invalid $entityClass provided"], 400);
        }

        return $entity;
    }

    #[Route('/dashbord', name: 'app_dashbord1', methods:["GET"])]
    public function index(): Response
    {
        $entityManager = $this->getDoctrine();

        $validStatus = 'valide';
        $Publications = $entityManager->getRepository(Publications::class)->findBy(['statut' => $validStatus]);


        //Ressources
        $RessourcesRepository = $entityManager->getManager()->getRepository(Pharmacy::class);
        $validStatus = 'En attente';
        $count1 = $RessourcesRepository->countByStatus($validStatus);
        //Etablissements
        $EtablissementsRepository = $entityManager->getManager()->getRepository(Establishments::class);
        $validStatus = 'En attente';
        $count2 = $EtablissementsRepository->countByStatus($validStatus);
        //Associations
        $AssociationsRepository = $entityManager->getManager()->getRepository(Associations::class);
        $validStatus = 'En attente';
        $count3 = $AssociationsRepository->countByStatus($validStatus);


        // return $this->json($count3);
        $data = [];
        $data2 = [
            ['pharmacies.total' => $count1,
            'centers.total' => $count2,
            'associations.total' => $count3]
        ];
        foreach ($Publications as $Publication) {
            $data[] = [
                'id' => $Publication->getId(),
                'titre' => $Publication->getTitre(),
                'image' => $Publication->getImage(),
                'desc' => $Publication->getContenu(),
            ];
        }

        $responseData = [
            'data' => $data,
            'data2' => $data2,
        ];

        return $this->json($responseData);
    }
}

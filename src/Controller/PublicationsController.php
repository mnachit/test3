<?php

namespace App\Controller;

use App\Entity\Ciblage;
use App\Entity\EmplacementPublication;
use App\Entity\Publications;
use App\Entity\TargetedPeople;
use App\Entity\TypePublication;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class PublicationsController extends AbstractController
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
            return null;
        }

        return $entity;
    }
    #[Route('/publications', name: 'app_publications')]
    public function index(): Response
    {
        $Publications = $this->em->getRepository(Publications::class)->findAll();

        $data = [];

        foreach ($Publications as $Publication) {
            $data[] = [
                'id' => $Publication->getId(),
                'titre' => $Publication->getTitre(),
                'ciblage' => $Publication->getCiblage()->getName(),
                'type' => $Publication->getType()->getName(),
                'statut' => $Publication->getStatut(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/publications/{id}', name: 'app_publications_id', methods: ["GET"])]
    public function show($id): Response
    {
        $Publication = $this->em->getRepository(Publications::class)->find($id);

        if (!$Publication) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [];
        $data = [
            'id' => $Publication->getId(),
            'titre' => $Publication->getTitre(),
            'ciblage' => $Publication->getCiblage()->getName(),
            'type' => $Publication->getType()->getName(),
            'statut' => $Publication->getStatut(),
        ];

        return $this->json($data);
    }

    #[Route('/publications/store', name: 'app_publications_store', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $Publications = new Publications();

        $Ciblage = $request->request->get('Ciblage');
        $type = $request->request->get('type');
        $Emplacement = $request->request->get('Emplacement');
        $Personnes = $request->request->get('Personnes');
        $lien_image = $request->request->get('lien_image');
        $lien_video = $request->request->get('lien_video');
        $Titre = $request->request->get('Titre');
        $Contenu = $request->request->get('Contenu');

        $lien_image = $this->validateEmpty($lien_image);
        $Contenu = $this->validateEmpty($Contenu);

        $CiblageRessources = $this->validateEntityById(Ciblage::class, $Ciblage);
        $typeRessources = $this->validateEntityById(TypePublication::class, $type);
        $EmplacementRessources = $this->validateEntityById(EmplacementPublication::class, $Emplacement);
        $PersonnesRessources = $this->validateEntityById(TargetedPeople::class, $Personnes);

        $validator = Validation::createValidator();
        $violations = $validator->validate([
            'Ciblage' => $Ciblage,
            'type' => $type,
            'Emplacement' => $Emplacement,
            'Personnes' => $Personnes,
            'Titre' => $Titre,
        ], new Assert\Collection([
            'Ciblage' => new Assert\NotBlank(),
            'type' => new Assert\NotBlank(),
            'Emplacement' => new Assert\NotBlank(),
            'Personnes' => new Assert\NotBlank(),
            'Titre' => new Assert\NotBlank(),
        ]));

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return new JsonResponse(['errors' => $errors], 400);
        }

        $Publications->setCiblage($CiblageRessources);
        $Publications->setType($typeRessources);
        $Publications->setEmplacement($EmplacementRessources);
        $Publications->setPeople($PersonnesRessources);
        $Publications->setLienImage($lien_image);
        $Publications->setLienVideo($lien_video);
        $Publications->setTitre($Titre);
        $Publications->setContenu($Contenu);
        $Publications->setStatut("En attente");
        $Publications->setRaisonDeRejet("--");
        $Publications->setDateCreated(new \DateTime());
        $Publications->setDateModified(new \DateTime());
        $Publications->setImage("https://api.posts.sobrus.com/uploads/images/posts/2023-06-06-16-07-23-128449-647f59bbc6737783204584.png");

        $this->em->persist($Publications);
        $this->em->flush();

        return $this->json(array('message' => 'Data stored successfully'), 201);
    }


    #[Route('/publications/edit/{id}', name: 'app_publications_update', methods: ["POST"])]
    public function update(Request $request, $id): JsonResponse
    {
        $Publications = $this->em->getRepository(Publications::class)->find($id);

        $Ciblage = $request->request->get('Ciblage');
        $type = $request->request->get('type');
        $Emplacement = $request->request->get('Emplacement');
        $Personnes = $request->request->get('Personnes');
        $lien_image = $request->request->get('lien_image');
        $lien_video = $request->request->get('lien_video');
        $Titre = $request->request->get('Titre');
        $Contenu = $request->request->get('Contenu');

        $lien_image = $this->validateEmpty($lien_image);
        $Contenu = $this->validateEmpty($Contenu);

        $CiblageRessources = $this->validateEntityById(Ciblage::class, $Ciblage);
        $typeRessources = $this->validateEntityById(TypePublication::class, $type);
        $EmplacementRessources = $this->validateEntityById(EmplacementPublication::class, $Emplacement);
        $PersonnesRessources = $this->validateEntityById(TargetedPeople::class, $Personnes);

        $validator = Validation::createValidator();
        $violations = $validator->validate([
            'Ciblage' => $Ciblage,
            'type' => $type,
            'Emplacement' => $Emplacement,
            'Personnes' => $Personnes,
            'Titre' => $Titre,
        ], new Assert\Collection([
            'Ciblage' => new Assert\NotBlank(),
            'type' => new Assert\NotBlank(),
            'Emplacement' => new Assert\NotBlank(),
            'Personnes' => new Assert\NotBlank(),
            'Titre' => new Assert\NotBlank(),
        ]));

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return new JsonResponse(['errors' => $errors], 400);
        }

        $Publications->setCiblage($CiblageRessources);
        $Publications->setType($typeRessources);
        $Publications->setEmplacement($EmplacementRessources);
        $Publications->setPeople($PersonnesRessources);
        $Publications->setLienImage($lien_image);
        $Publications->setLienVideo($lien_video);
        $Publications->setTitre($Titre);
        $Publications->setContenu($Contenu);
        $Publications->setStatut("En attente");
        $Publications->setRaisonDeRejet("--");
        $Publications->setDateCreated(new \DateTime());
        $Publications->setDateModified(new \DateTime());
        $Publications->setImage("https://api.posts.sobrus.com/uploads/images/posts/2023-06-06-16-07-23-128449-647f59bbc6737783204584.png");

        $this->em->flush();

        return $this->json(array('message' => 'Data Update successfully'), 201);
    }


    #[Route('/publications/destroy/{id}', name: 'app_publications_destroy', methods: ["DELETE"])]
    public function destroy($id): JsonResponse
    {
        $Publication = $this->em->getRepository(Publications::class)->find($id);

        if (!$Publication) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $this->em->remove($Publication);
        $this->em->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

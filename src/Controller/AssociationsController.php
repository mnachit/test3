<?php

namespace App\Controller;

use App\Entity\Pays;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Associations;
use App\Entity\Secteur;
use App\Entity\Ville;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class AssociationsController extends AbstractController
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

    #[Route('/Associations', name: 'app_Associations', methods: ["GET"])]
    public function index(): JsonResponse
    {
        $repository = $this->em->getRepository(Associations::class);
        $queryBuilder = $repository->createQueryBuilder('p');
        $queryBuilder->where('p.statut = :statut')
            ->setParameter('statut', 'Valide');
        $Associationss = $queryBuilder->getQuery()->getResult();

        $data = [];

        foreach ($Associationss as $Associations) {
            $villeNom = $Associations->getVille()->getName();
            $secteurNom = $Associations->getSecteur()->getName();

            $data[] = [
                'id' => $Associations->getId(),
                'Nom' => $Associations->getName(),
                'Ville' => $villeNom,
                'secteur' => $secteurNom
            ];
        }

        return $this->json($data);
    }

    #[Route('/Associations/H', name: 'app_Associations_H', methods: ["GET"])]
    public function index_H(): JsonResponse
    {
        $Associationss = $this->em->getRepository(Associations::class)->findAll();
        $data = [];
        foreach ($Associationss as $Associations) {
            $villeNom = $Associations->getVille()->getName();
            $secteurNom = $Associations->getSecteur()->getName();
            $data[] = [
                'id' => $Associations->getId(),
                'Nom' => $Associations->getName(),
                'Ville' => $villeNom,
                'secteur' => $secteurNom,
                'Statut' => $Associations->getStatut(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/Associations/{id}', name: 'app_Associations_show', methods: ["GET"])]
    public function show($id): JsonResponse
    {

        $Associations = $this->em->getRepository(Associations::class)->find($id);

        if (!$Associations) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $villeNom = $Associations->getVille()->getName();
        $secteurNom = $Associations->getSecteur()->getName();
        $data[] = [
            'id' => $Associations->getId(),
            'Nom' => $Associations->getName(),
            'Ville' => $villeNom,
            'secteur' => $secteurNom,
            'Statut' => $Associations->getStatut(),
        ];

        return new JsonResponse($data);
    }

    #[Route('/Associations/store', name: 'app_Associations_store', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $Associations = new Associations();
        $nom = $request->request->get('nom');
        $email = $request->request->get('email');
        $telephone = $request->request->get('telephone');
        $portable = $request->request->get('portable');
        $fax = $request->request->get('fax');
        $site_internet = $request->request->get('site_internet');
        $RC = $request->request->get('RC');
        $INPE = $request->request->get('INPE');
        $adresse = $request->request->get('adresse');
        $region = $request->request->get('region');
        $code_postal = $request->request->get('code_postal');

        $ville = $request->request->get('ville');
        $secteur = $request->request->get('secteur');
        $pays = $request->request->get('pays');

        $email = $this->validateEmpty($email);
        $telephone = $this->validateEmpty($telephone);
        $fax = $this->validateEmpty($fax);
        $site_internet = $this->validateEmpty($site_internet);
        $RC = $this->validateEmpty($RC);
        $INPE = $this->validateEmpty($INPE);
        $adresse = $this->validateEmpty($adresse);
        $region = $this->validateEmpty($region);
        $code_postal = $this->validateEmpty($code_postal);
        if (empty($secteur)) {
            $secteur = 1;
        }
        $ville_id = $this->validateEntityById(Ville::class, $ville);
        $pays_id = $this->validateEntityById(Pays::class, $pays);
        $secteur_id = $this->validateEntityById(Secteur::class, $secteur);

        $validator = Validation::createValidator();
        $violations = $validator->validate([
            'nom' => $nom,
            'portable' => $portable,
            'ville' => $ville,
            'pays' => $pays,
        ], new Assert\Collection([
            'nom' => new Assert\NotBlank(),
            'portable' => new Assert\NotBlank(),
            'ville' => new Assert\NotBlank(),
            'pays' => new Assert\NotBlank(),
        ]));

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return new JsonResponse(['errors' => $errors], 400);
        } else {
            $Associations->setName($nom);
            $Associations->setEmail($email);
            $Associations->setTelephone($telephone);
            $Associations->setPortable($portable);
            $Associations->setFax($fax);
            $Associations->setSiteInternet($site_internet);
            $Associations->setRC($RC);
            $Associations->setINPE($INPE);
            $Associations->setAdresse($adresse);
            $Associations->setRegion($region);
            $Associations->setCodePostale($code_postal);
            $Associations->setSecteur($secteur_id);
            $Associations->setVille($ville_id);
            $Associations->setPays($pays_id);
            $Associations->setstatut("En attente");
            $Associations->setReasonForRejection("--");
            $Associations->setDateCreated(new \DateTime());
            $Associations->setDateModified(new \DateTime());

            $this->em->persist($Associations);
            $this->em->flush();

            return $this->json(array('message' => 'Data stored successfully'), 201);
        }
    }

    #[Route('/Associations/update/{id}', name: 'app_Associations_update', methods: ["POST"])]
    public function update($id, Request $request)
    {
        $Associations = $this->em->getRepository(Associations::class)->find($id);

        if (!$Associations) {
            return new JsonResponse(['error' => 'Product not found'], 404);
        }

        $nom = $request->request->get('nom');
        $email = $request->request->get('email');
        $telephone = $request->request->get('telephone');
        $portable = $request->request->get('portable');
        $fax = $request->request->get('fax');
        $site_internet = $request->request->get('site_internet');
        $RC = $request->request->get('RC');
        $INPE = $request->request->get('INPE');
        $adresse = $request->request->get('adresse');
        $region = $request->request->get('region');
        $code_postal = $request->request->get('code_postal');

        $ville = $request->request->get('ville');
        $secteur = $request->request->get('secteur');
        $pays = $request->request->get('pays');

        $email = $this->validateEmpty($email);
        $telephone = $this->validateEmpty($telephone);
        $fax = $this->validateEmpty($fax);
        $site_internet = $this->validateEmpty($site_internet);
        $RC = $this->validateEmpty($RC);
        $INPE = $this->validateEmpty($INPE);
        $adresse = $this->validateEmpty($adresse);
        $region = $this->validateEmpty($region);
        $code_postal = $this->validateEmpty($code_postal);
        if (empty($secteur)) {
            $secteur = 1;
        }
        $ville_id = $this->validateEntityById(Ville::class, $ville);
        $pays_id = $this->validateEntityById(Pays::class, $pays);
        $secteur_id = $this->validateEntityById(Secteur::class, $secteur);

        $validator = Validation::createValidator();
        $violations = $validator->validate([
            'nom' => $nom,
            'portable' => $portable,
            'ville' => $ville,
            'pays' => $pays,
        ], new Assert\Collection([
            'nom' => new Assert\NotBlank(),
            'portable' => new Assert\NotBlank(),
            'ville' => new Assert\NotBlank(),
            'pays' => new Assert\NotBlank(),
        ]));

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return new JsonResponse(['errors' => $errors], 400);
        }
        
        $Associations->setName($nom);
        $Associations->setEMail($email);
        $Associations->setTelephone($telephone);
        $Associations->setPortable($portable);
        $Associations->setFax($fax);
        $Associations->setSiteInternet($site_internet);
        $Associations->setRC($RC);
        $Associations->setINPE($INPE);
        $Associations->setAdresse($adresse);
        $Associations->setRegion($region);
        $Associations->setCodePostale($code_postal);
        $Associations->setSecteur($secteur_id);
        $Associations->setVille($ville_id);
        $Associations->setPays($pays_id);
        $Associations->setstatut("En attente");
        $Associations->setReasonForRejection("--");
        $Associations->setDateCreated(new \DateTime());
        $Associations->setDateModified(new \DateTime());

        $this->em->flush();

        return $this->json(array('message' => 'Data Update successfully'), 201);
    }
}

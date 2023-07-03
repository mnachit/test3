<?php

namespace App\Controller;

use App\Entity\Pays;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Establishments;
use App\Entity\Secteur;
use App\Entity\SpecialityEstablishments;
use App\Entity\TypeEstablishments;
use App\Entity\Ville;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class EstablishmentsController extends AbstractController
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

    #[Route('/Establishments', name: 'app_Establishments', methods: ["GET"])]
    public function index(): JsonResponse
    {
        $repository = $this->em->getRepository(Establishments::class);
        $queryBuilder = $repository->createQueryBuilder('p');
        $queryBuilder->where('p.statut = :statut')
            ->setParameter('statut', 'Valide');
        $Establishmentss = $queryBuilder->getQuery()->getResult();

        $data = [];

        foreach ($Establishmentss as $Establishments) {
            $villeNom = $Establishments->getVille()->getName();
            $secteurNom = $Establishments->getSecteur()->getName();

            $data[] = [
                'id' => $Establishments->getId(),
                'Nom' => $Establishments->getName(),
                'Ville' => $villeNom,
                'secteur' => $secteurNom
            ];
        }

        return $this->json($data);
    }

    #[Route('/Establishments/H', name: 'app_Establishments_H', methods: ["GET"])]
    public function index_H(): JsonResponse
    {
        $Establishmentss = $this->em->getRepository(Establishments::class)->findAll();
        $data = [];
        foreach ($Establishmentss as $Establishments) {
            $villeNom = $Establishments->getVille()->getName();
            $secteurNom = $Establishments->getSecteur()->getName();
            $data[] = [
                'id' => $Establishments->getId(),
                'Nom' => $Establishments->getName(),
                'Ville' => $villeNom,
                'secteur' => $secteurNom,
                'Statut' => $Establishments->getStatut(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/Establishments/{id}', name: 'app_Establishments_show', methods: ["GET"])]
    public function show($id): JsonResponse
    {

        $Establishments = $this->em->getRepository(Establishments::class)->find($id);

        if (!$Establishments) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $villeNom = $Establishments->getVille()->getName();
        $secteurNom = $Establishments->getSecteur()->getName();
        $data[] = [
            'id' => $Establishments->getId(),
            'Nom' => $Establishments->getName(),
            'Ville' => $villeNom,
            'secteur' => $secteurNom,
            'Statut' => $Establishments->getStatut(),
        ];

        return new JsonResponse($data);
    }

    #[Route('/Establishments/store', name: 'app_Establishments_store', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $Establishments = new Establishments();
        $nom = $request->request->get('nom');
        $Speciality = $request->request->get('Speciality');
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
        $type = $request->request->get('type');

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
        $Speciality_id = $this->validateEntityById(SpecialityEstablishments::class, $Speciality);
        $type_id = $this->validateEntityById(TypeEstablishments::class, $type);
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
            $Establishments->setName($nom);
            $Establishments->setEmail($email);
            $Establishments->setTelephone($telephone);
            $Establishments->setPortable($portable);
            $Establishments->setFax($fax);
            $Establishments->setSiteInternet($site_internet);
            $Establishments->setRC($RC);
            $Establishments->setINPE($INPE);
            $Establishments->setAdresse($adresse);
            $Establishments->setRegion($region);
            $Establishments->setCodePostale($code_postal);
            $Establishments->setSecteur($secteur_id);
            $Establishments->setVille($ville_id);
            $Establishments->setPays($pays_id);
            $Establishments->setType($type_id);
            $Establishments->setSpeciality($Speciality_id);
            $Establishments->setstatut("En attente");
            $Establishments->setReasonForRejection("--");
            $Establishments->setDateCreated(new \DateTime());
            $Establishments->setDateModified(new \DateTime());

            $this->em->persist($Establishments);
            $this->em->flush();

            return $this->json(array('message' => 'Data stored successfully'), 201);
        }
    }

    #[Route('/Establishments/update/{id}', name: 'app_Establishments_update', methods: ["POST"])]
    public function update($id, Request $request)
    {
        $Establishments = $this->em->getRepository(Establishments::class)->find($id);

        if (!$Establishments) {
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
        $type = $request->request->get('type');

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

        $type_id = $this->validateEntityById(TypeEstablishments::class, $type);
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
        
        $Establishments->setName($nom);
        $Establishments->setEMail($email);
        $Establishments->setTelephone($telephone);
        $Establishments->setPortable($portable);
        $Establishments->setFax($fax);
        $Establishments->setSiteInternet($site_internet);
        $Establishments->setRC($RC);
        $Establishments->setINPE($INPE);
        $Establishments->setAdresse($adresse);
        $Establishments->setRegion($region);
        $Establishments->setCodePostale($code_postal);
        $Establishments->setSecteur($secteur_id);
        $Establishments->setVille($ville_id);
        $Establishments->setPays($pays_id);
        $Establishments->setType($type_id);
        $Establishments->setstatut("En attente");
        $Establishments->setReasonForRejection("--");
        $Establishments->setDateCreated(new \DateTime());
        $Establishments->setDateModified(new \DateTime());

        $this->em->flush();

        return $this->json(array('message' => 'Data Update successfully'), 201);
    }
}

<?php

namespace App\Controller;

use App\Entity\Pays;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Pharmacy;
use App\Entity\Secteur;
use App\Entity\TypePharmacy;
use App\Entity\Ville;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class PharmacyController extends AbstractController
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

    #[Route('/pharmacy', name: 'app_pharmacy', methods: ["GET"])]
    public function index(): JsonResponse
    {
        $repository = $this->em->getRepository(Pharmacy::class);
        $queryBuilder = $repository->createQueryBuilder('p');
        $queryBuilder->where('p.statut = :statut')
            ->setParameter('statut', 'Valide');
        $Pharmacys = $queryBuilder->getQuery()->getResult();

        $data = [];

        foreach ($Pharmacys as $Pharmacy) {
            $villeNom = $Pharmacy->getVille()->getName();
            $secteurNom = $Pharmacy->getSecteur()->getName();

            $data[] = [
                'id' => $Pharmacy->getId(),
                'Nom' => $Pharmacy->getName(),
                'Ville' => $villeNom,
                'secteur' => $secteurNom
            ];
        }

        return $this->json($data);
    }

    #[Route('/pharmacy/H', name: 'app_Pharmacy_H', methods: ["GET"])]
    public function index_H(): JsonResponse
    {
        $Pharmacys = $this->em->getRepository(Pharmacy::class)->findAll();
        $data = [];
        foreach ($Pharmacys as $Pharmacy) {
            $villeNom = $Pharmacy->getVille()->getName();
            $secteurNom = $Pharmacy->getSecteur()->getName();
            $data[] = [
                'id' => $Pharmacy->getId(),
                'Nom' => $Pharmacy->getName(),
                'Ville' => $villeNom,
                'secteur' => $secteurNom,
                'Statut' => $Pharmacy->getStatut(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/pharmacy/{id}', name: 'app_Pharmacy_show', methods: ["GET"])]
    public function show($id): JsonResponse
    {

        $Pharmacy = $this->em->getRepository(Pharmacy::class)->find($id);

        if (!$Pharmacy) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $villeNom = $Pharmacy->getVille()->getName();
        $secteurNom = $Pharmacy->getSecteur()->getName();
        $data[] = [
            'id' => $Pharmacy->getId(),
            'Nom' => $Pharmacy->getName(),
            'Ville' => $villeNom,
            'secteur' => $secteurNom,
            'Statut' => $Pharmacy->getStatut(),
        ];

        return new JsonResponse($data);
    }

    #[Route('/pharmacy/store', name: 'app_pharmacy_store', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $Pharmacy = new Pharmacy();
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

        $type_id = $this->validateEntityById(TypePharmacy::class, $type);
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
            $Pharmacy->setName($nom);
            $Pharmacy->setEmail($email);
            $Pharmacy->setTelephone($telephone);
            $Pharmacy->setPortable($portable);
            $Pharmacy->setFax($fax);
            $Pharmacy->setSiteInternet($site_internet);
            $Pharmacy->setRC($RC);
            $Pharmacy->setINPE($INPE);
            $Pharmacy->setAdresse($adresse);
            $Pharmacy->setRegion($region);
            $Pharmacy->setCodePostale($code_postal);
            $Pharmacy->setSecteur($secteur_id);
            $Pharmacy->setVille($ville_id);
            $Pharmacy->setPays($pays_id);
            $Pharmacy->setType($type_id);
            $Pharmacy->setstatut("En attente");
            $Pharmacy->setReasonForRejection("--");
            $Pharmacy->setDateCreated(new \DateTime());
            $Pharmacy->setDateModified(new \DateTime());

            $this->em->persist($Pharmacy);
            $this->em->flush();

            return $this->json(array('message' => 'Data stored successfully'), 201);
        }
    }

    #[Route('/pharmacy/update/{id}', name: 'app_pharmacy_update', methods: ["POST"])]
    public function update($id, Request $request)
    {
        $pharmacy = $this->em->getRepository(Pharmacy::class)->find($id);

        if (!$pharmacy) {
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

        $type_id = $this->validateEntityById(TypePharmacy::class, $type);
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
        
        $pharmacy->setName($nom);
        $pharmacy->setEMail($email);
        $pharmacy->setTelephone($telephone);
        $pharmacy->setPortable($portable);
        $pharmacy->setFax($fax);
        $pharmacy->setSiteInternet($site_internet);
        $pharmacy->setRC($RC);
        $pharmacy->setINPE($INPE);
        $pharmacy->setAdresse($adresse);
        $pharmacy->setRegion($region);
        $pharmacy->setCodePostale($code_postal);
        $pharmacy->setSecteur($secteur_id);
        $pharmacy->setVille($ville_id);
        $pharmacy->setPays($pays_id);
        $pharmacy->setType($type_id);
        $pharmacy->setstatut("En attente");
        $pharmacy->setReasonForRejection("--");
        $pharmacy->setDateCreated(new \DateTime());
        $pharmacy->setDateModified(new \DateTime());

        $this->em->flush();

        return $this->json(array('message' => 'Data Update successfully'), 201);
    }

    #[Route('/pharmacy/destroy/{id}', name: 'app_pharmacy_destroy', methods: ["DELETE"])]
    public function destroy($id): JsonResponse
    {
        $pharmacy = $this->em->getRepository(Pharmacy::class)->find($id);

        if (!$pharmacy) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $this->em->remove($pharmacy);
        $this->em->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

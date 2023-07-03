<?php

namespace App\Controller;

use App\Entity\Categorycontacts;
use App\Entity\Compte;
use App\Entity\Contact;
use App\Entity\TypePerson;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ContactController extends AbstractController
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

    #[Route('/contact/H', name: 'app_contact_H', methods: ["GET"])]
    public function index_H(): Response
    {
        $repository = $this->em->getRepository(Contact::class)->findall();

        $data = [];

        foreach ($repository as $Contact) {
            $compte = $Contact->getCompte()->getName();

            $Category = $Contact->getCategory()->getName();

            $data[] = [
                'id' => $Contact->getId(),
                'Compte' => $compte,
                'prenom' => $Contact->getFirst(),
                'nom' => $Contact->getLast(),
                'portabele' => $Contact->getPortable(),
                'category' => $Category
            ];
        }

        return $this->json($data);
    }

    #[Route('/contact/{id}', name: 'app_contact_', methods: ["GET"])]
    public function show($id): Response
    {
        $Contact = $this->em->getRepository(Contact::class)->find($id);

        if (!$Contact) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [];

        $compte = $Contact->getCompte()->getName();

        $Category = $Contact->getCategory()->getName();

        $data[] = [
            'id' => $Contact->getId(),
            'Compte' => $compte,
            'prenom' => $Contact->getFirst(),
            'nom' => $Contact->getLast(),
            'portabele' => $Contact->getPortable(),
            'category' => $Category
        ];

        return $this->json($data);
    }

    #[Route('/contact', name: 'app_contact_index', methods: ["GET"])]
    public function index(): Response
    {
        $repository = $this->em->getRepository(Contact::class);
        $queryBuilder = $repository->createQueryBuilder('p');
        $queryBuilder->where('p.statut = :statut')
            ->setParameter('statut', 'Valide');
        $Contacts = $queryBuilder->getQuery()->getResult();

        $data = [];

        foreach ($Contacts as $Contact) {
            $compte = $Contact->getCompte()->getName();

            $Category = $Contact->getCategory()->getName();

            $data[] = [
                'id' => $Contact->getId(),
                'Compte' => $compte,
                'prenom' => $Contact->getFirst(),
                'nom' => $Contact->getLast(),
                'portabele' => $Contact->getPortable(),
                'category' => $Category
            ];
        }

        return $this->json($data);
    }

    #[Route('/contact/store', name: 'app_contact_store', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {

        $Contact = new Contact();

        $user_id = $request->request->get('user');
        $compteId = $request->request->get('Compte');
        $prenom = $request->request->get('prenom');
        $nom = $request->request->get('nom');
        $titre = $request->request->get('titre');
        $category = $request->request->get('category');
        $email = $request->request->get('email');
        $portabel = $request->request->get('portabel');
        $poste = $request->request->get('poste');
        $service = $request->request->get('service');

        $compte = $this->validateEntityById(Compte::class, $compteId);
        $titreid = $this->validateEntityById(TypePerson::class, $titre);
        $categoryid = $this->validateEntityById(Categorycontacts::class, $category);


        $validator = Validation::createValidator();
        $violations = $validator->validate([
            'Gestionnaire' => $user_id,
            'compte' => $compteId,
            'prenom' => $prenom,
            'nom' => $nom,
            'Categorie' => $category,
        ], new Assert\Collection([
            'Gestionnaire' => new Assert\NotBlank(),
            'compte' => new Assert\NotBlank(),
            'prenom' => new Assert\NotBlank(),
            'nom' => new Assert\NotBlank(),
            'Categorie' => new Assert\NotBlank(),
        ]));

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['errors' => $errors], 400);
        } else {
            $Contact->setCompte($compte);
            $Contact->setFirst($prenom);
            $Contact->setLast($nom);
            $Contact->setEmail($email);
            $Contact->setPoste($poste);
            $Contact->setService($service);
            $Contact->setPortable($portabel);
            $Contact->setTitre($titreid);
            $Contact->setCategory($categoryid);

            $this->em->persist($Contact);
            $this->em->flush();
        }

        return $this->json(array('message' => 'Data stored successfully'), 201);
    }

    #[Route('/contact/update/{id}', name: 'app_contact_store_u', methods: ["POST"])]
    public function Update(Request $request, $id): JsonResponse
    {

        $Contact = $this->em->getRepository(Contact::class)->find($id);

        $user_id = $request->request->get('user');
        $compteId = $request->request->get('Compte');
        $prenom = $request->request->get('prenom');
        $nom = $request->request->get('nom');
        $titre = $request->request->get('titre');
        $category = $request->request->get('category');
        $email = $request->request->get('email');
        $portabel = $request->request->get('portabel');
        $poste = $request->request->get('poste');
        $service = $request->request->get('service');

        $compte = $this->validateEntityById(Compte::class, $compteId);
        $titreid = $this->validateEntityById(TypePerson::class, $titre);
        $categoryid = $this->validateEntityById(Categorycontacts::class, $category);


        $validator = Validation::createValidator();
        $violations = $validator->validate([
            'Gestionnaire' => $user_id,
            'compte' => $compteId,
            'prenom' => $prenom,
            'nom' => $nom,
            'Categorie' => $category,
        ], new Assert\Collection([
            'Gestionnaire' => new Assert\NotBlank(),
            'compte' => new Assert\NotBlank(),
            'prenom' => new Assert\NotBlank(),
            'nom' => new Assert\NotBlank(),
            'Categorie' => new Assert\NotBlank(),
        ]));

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['errors' => $errors], 400);
        } else {
            $Contact->setCompte($compte);
            $Contact->setFirst($prenom);
            $Contact->setLast($nom);
            $Contact->setEmail($email);
            $Contact->setPoste($poste);
            $Contact->setService($service);
            $Contact->setPortable($portabel);
            $Contact->setTitre($titreid);
            $Contact->setCategory($categoryid);

            $this->em->flush();
        }

        return $this->json(array('message' => 'Data update successfully'), 201);
    }

    #[Route('/contact/destroy/{id}', name: 'app_Contact_destroy', methods: ["DELETE"])]
    public function destroy($id): JsonResponse
    {
        $Contacts = $this->em->getRepository(Contact::class)->find($id);

        if (!$Contacts) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $this->em->remove($Contacts);
        $this->em->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

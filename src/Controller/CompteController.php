<?php

namespace App\Controller;

use App\Entity\Associations;
use App\Entity\CategoryCompte;
use App\Entity\Compte;
use App\Entity\Establishments;
use App\Entity\Pharmacy;
use App\Entity\TypeCompte;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;


#[Route('/api/comptes')]
class CompteController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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

    // #[Route('/', name: 'app_compte_H')]
    public function index_H(): JsonResponse
    {
        $repository = $this->em->getRepository(Compte::class)->findall();

        $data = [];

        foreach ($repository as $compte) {
            $Type = $compte->getType()->getName();
            $category = $compte->getCategory()->getName();
            $statut = $compte->getStatut();
            if ($compte->getEtablissement() == null && $compte->getAssociation() == null) {
                $ville = $compte->getPharmacy()->getVille()->getName();
                $secteurNom = $compte->getPharmacy()->getSecteur()->getName();
                $Nom = $compte->getPharmacy()->getName();
            } else if ($compte->getEtablissement() == null && $compte->getPharmacy() == null) {
                $ville = $compte->getAssociation()->getVille()->getName();
                $secteurNom = $compte->getAssociation()->getSecteur()->getName();
                $Nom = $compte->getAssociation()->getName();
            } else if ($compte->getPharmacy() == null && $compte->getAssociation() == null) {
                $ville = $compte->getEtablissement()->getVille()->getName();
                $secteurNom = $compte->getEtablissement()->getSecteur()->getName();
                $Nom = $compte->getEtablissement()->getName();
            }
            $data[] = [
                'id' => $compte->getId(),
                'type' => $Type,
                'Nom' => $Nom,
                'category' => $category,
                'ville' => $ville,
                'secteur' => $secteurNom,
                'statut' => $statut,
            ];
        }

        return $this->json($data);
    }

    /**
     * this end point returns a list of accounts
     * 
     * @OA\Get(
     *     path="/api/comptes",
     *     summary="Get all comptes",
     *     tags={"Comptes"},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref=@Model(type=Compte::class))
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */
    #[Route('/', name: 'app_compte', methods:["GET"])]
    public function index(): JsonResponse
    {
        $repository = $this->em->getRepository(Compte::class);
        $queryBuilder = $repository->createQueryBuilder('p');
        $queryBuilder->where('p.statut = :statut')
            ->setParameter('statut', 'Valide');
        $repositorys = $queryBuilder->getQuery()->getResult();

        $data = [];

        foreach ($repositorys as $compte) {
            $Type = $compte->getType()->getName();
            $category = $compte->getCategory()->getName();
            $statut = $compte->getStatut();
            if ($compte->getEtablissement() == null && $compte->getAssociation() == null) {
                $ville = $compte->getPharmacy()->getVille()->getName();
                $secteurNom = $compte->getPharmacy()->getSecteur()->getName();
                $Nom = $compte->getPharmacy()->getName();
            } else if ($compte->getEtablissement() == null && $compte->getPharmacy() == null) {
                $ville = $compte->getAssociation()->getVille()->getName();
                $secteurNom = $compte->getAssociation()->getSecteur()->getName();
                $Nom = $compte->getAssociation()->getName();
            } else if ($compte->getPharmacy() == null && $compte->getAssociation() == null) {
                $ville = $compte->getEtablissement()->getVille()->getName();
                $secteurNom = $compte->getEtablissement()->getSecteur()->getName();
                $Nom = $compte->getEtablissement()->getName();
            }
            $data[] = [
                'id' => $compte->getId(),
                'type' => $Type,
                'Nom' => $Nom,
                'category' => $category,
                'ville' => $ville,
                'secteur' => $secteurNom,
                'statut' => $statut,
            ];
        }

        return $this->json($data);
    }

    /**
     * this end poitn is resposible for creating a new account
     * @OA\Post(
     *     path="/api/comptes/store",
     *     summary="Store a new compte",
     *     tags={"Comptes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="Gestionnaire", type="string"),
     *             @OA\Property(property="Exact_type", type="integer"),
     *             @OA\Property(property="Type_de_relation", type="integer"),
     *             @OA\Property(property="Nom_du_compte", type="string"),
     *             @OA\Property(property="Type_du_compte", type="integer"),
     *             @OA\Property(property="Categorie", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */
    #[Route('/store', name: 'compte_store', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $compte = new Compte();

        $Gestionnaire = $request->request->get('Gestionnaire');
        $Exact_type = $request->request->get('Exact_type');
        $Type_de_relation = (int) $request->request->get('Type_de_relation');
        $Nom_du_compte = $request->request->get('Nom_du_compte');
        $Type_du_compte = $request->request->get('Type_du_compte');
        $Categorie = $request->request->get('Categorie');

        if ($Type_de_relation === 1) {
            $type_id = $this->validateEntityById(Pharmacy::class, $Exact_type);
        } else if ($Type_de_relation === 2) {
            $type_id = $this->validateEntityById(Establishments::class, $Exact_type);
        } else if ($Type_de_relation === 3) {
            $type_id = $this->validateEntityById(Associations::class, $Exact_type);
        } else {
            return $this->json('Invalid Type provided');
        }

        $type = $this->validateEntityById(TypeCompte::class, $Type_du_compte);
        $Categorie = $this->validateEntityById(CategoryCompte::class, $Categorie);

        $validator = Validation::createValidator();
        $violations = $validator->validate([
            'Gestionnaire' => $Gestionnaire,
            'Type_de_relation' => $Type_de_relation,
            'Nom_du_compte' => $Nom_du_compte,
            'Type_du_compte' => $Type_du_compte,
            'Categorie' => $Categorie,
        ], new Assert\Collection([
            'Gestionnaire' => new Assert\NotBlank(),
            'Type_de_relation' => new Assert\NotBlank(),
            'Nom_du_compte' => new Assert\NotBlank(),
            'Type_du_compte' => new Assert\NotBlank(),
            'Categorie' => new Assert\NotBlank(),
        ]));

        if ($Type_de_relation === 1) {
            $compte->setPharmacy($type_id);
        } else if ($Type_de_relation === 2) {
            $compte->setEtablissement($type_id);
        } else if ($Type_de_relation === 3) {
            $compte->setAssociation($type_id);
        } else {
            return $this->json('Invalid Type provided');
        }

        $compte->setName($Nom_du_compte);
        $compte->setType($type);
        $compte->setCategory($Categorie);
        $this->em->persist($compte);
        $this->em->flush();

        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    
    #[Route('/{id}', name: 'app_compte_', methods: ["GET"])]
    public function show(int $id): JsonResponse
    {
        $compte = $this->em->getRepository(Compte::class)->find($id);

        if (!$compte) {
            return new JsonResponse(['error' => 'Product not found'], 404);
        }

        $data = [];
        $Type = $compte->getType()->getName();
        $category = $compte->getCategory()->getName();
        if ($compte->getEtablissement() == null && $compte->getAssociation() == null) {
            $ville = $compte->getPharmacy()->getVille()->getName();
            $secteurNom = $compte->getPharmacy()->getSecteur()->getName();
            $Nom = $compte->getPharmacy()->getName();
        } else if ($compte->getEtablissement() == null && $compte->getPharmacy() == null) {
            $ville = $compte->getAssociation()->getVille()->getName();
            $secteurNom = $compte->getAssociation()->getSecteur()->getName();
            $Nom = $compte->getAssociation()->getName();
        } else if ($compte->getPharmacy() == null && $compte->getAssociation() == null) {
            $ville = $compte->getEtablissement()->getVille()->getName();
            $secteurNom = $compte->getEtablissement()->getSecteur()->getName();
            $Nom = $compte->getEtablissement()->getName();
        }
        $data[] = [
            'id' => $compte->getId(),
            'type' => $Type,
            'Nom' => $Nom,
            'category' => $category,
            'ville' => $ville,
            'secteur' => $secteurNom,
        ];

        return new JsonResponse($data);
    }

    #[Route('/update/{id}', name: 'compte_Update', methods: ["PUT", "PATCH", "POST"])]
    public function update(Request $request, int $id): JsonResponse
    {
        $compte = $this->em->getRepository(Compte::class)->find($id);

        $Gestionnaire = $request->request->get('Gestionnaire');
        $Exact_type = $request->request->get('Exact_type');
        $Type_de_relation = (int) $request->request->get('Type_de_relation');
        $Nom_du_compte = $request->request->get('Nom_du_compte');
        $Type_du_compte = $request->request->get('Type_du_compte');
        $Categorie = $request->request->get('Categorie');

        if ($Type_de_relation === 1) {
            $type_id = $this->validateEntityById(Pharmacy::class, $Exact_type);
        } else if ($Type_de_relation === 2) {
            $type_id = $this->validateEntityById(Establishments::class, $Exact_type);
        } else if ($Type_de_relation === 3) {
            $type_id = $this->validateEntityById(Associations::class, $Exact_type);
        } else {
            return $this->json('Invalid Type provided');
        }

        $type = $this->validateEntityById(TypeCompte::class, $Type_du_compte);
        $Categorie = $this->validateEntityById(CategoryCompte::class, $Categorie);

        $validator = Validation::createValidator();
        $violations = $validator->validate([
            'Gestionnaire' => $Gestionnaire,
            'Type_de_relation' => $Type_de_relation,
            'Nom_du_compte' => $Nom_du_compte,
            'Type_du_compte' => $Type_du_compte,
            'Categorie' => $Categorie,
        ], new Assert\Collection([
            'Gestionnaire' => new Assert\NotBlank(),
            'Type_de_relation' => new Assert\NotBlank(),
            'Nom_du_compte' => new Assert\NotBlank(),
            'Type_du_compte' => new Assert\NotBlank(),
            'Categorie' => new Assert\NotBlank(),
        ]));

        if ($Type_de_relation === 1) {
            $compte->setPharmacy($type_id);

            $compte->setEtablissement(null);
            $compte->setAssociation(null);
        } else if ($Type_de_relation === 2) {
            $compte->setEtablissement($type_id);

            $compte->setAssociation(null);
            $compte->setPharmacy(null);
        } else if ($Type_de_relation === 3) {
            $compte->setAssociation($type_id);

            $compte->setEtablissement(null);
            $compte->setPharmacy(null);
        } else {
            return $this->json('Invalid Type provided');
        }

        $compte->setName($Nom_du_compte);
        $compte->setType($type);
        $compte->setCategory($Categorie);

        $this->em->flush();

        return new JsonResponse(['message' => 'Data Update successfully']);
    }

    #[Route('destroy/{id}', name: 'destroy_compte',  methods: 'DELETE')]
    public function destroy(int $id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Compte::class)->find($id);

        if (!$category) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($category);
        $entityManager->flush();

        return new JsonResponse([
            "message" => "account deleted"
        ]);
    }
}

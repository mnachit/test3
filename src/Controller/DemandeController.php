<?php

namespace App\Controller;

use App\Entity\CanalDemande;
use App\Entity\Contact;
use App\Entity\Demande;
use App\Entity\PriorityDemande;
use App\Entity\RaisonDemande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class DemandeController extends AbstractController
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

    #[Route('/demande/H', name: 'app_demande_H')]
    public function index_H(): JsonResponse
    {
        $demandes = $this->em->getRepository(Demande::class)->findAll();



        $data = [];

        foreach ($demandes as $demande) {

            $contactf = $demande->getContact()->getFirst();
            $contactl = $demande->getContact()->getLast();
            $Priority = $demande->getPriority()->getName();
            $Canal = $demande->getCanal()->getName();

            $data[] = [
                'id' => $demande->getId(),
                'Object' => $demande->getObjet(),
                'Contact' => $contactf . ' ' . $contactl,
                // 'date' => $demande->getLast(),
                // 'date_m' => $demande->getPortable(),
                'Priority' => $Priority,
                'Canal' => $Canal,
                'Statut' => $demande->getStatut(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/demande', name: 'app_demande')]
    public function index(): JsonResponse
    {
        $repository = $this->em->getRepository(Demande::class);
        $queryBuilder = $repository->createQueryBuilder('p');
        $queryBuilder->where('p.statut = :statut')
            ->setParameter('statut', 'Valide');
        $demandes = $queryBuilder->getQuery()->getResult();

        $data = [];

        foreach ($demandes as $demande) {

            $contactf = $demande->getContact()->getFirst();
            $contactl = $demande->getContact()->getLast();
            $Priority = $demande->getPriority()->getName();
            $Canal = $demande->getCanal()->getName();

            $data[] = [
                'id' => $demande->getId(),
                'Object' => $demande->getObjet(),
                'Contact' => $contactf . ' ' . $contactl,
                // 'date' => $demande->getLast(),
                // 'date_m' => $demande->getPortable(),
                'Priority' => $Priority,
                'Canal' => $Canal,
                'Statut' => $demande->getStatut(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/demande/{id}', name: 'app_demande_sh', methods: ["GET"])]
    public function show($id): JsonResponse
    {
        $demande = $this->em->getRepository(Demande::class)->find($id);


        if (!$demande) {
            return $this->json(['message' => "ID doesn't exist"]);
        }
        $data = [];

        $contactf = $demande->getContact()->getFirst();
        $contactl = $demande->getContact()->getLast();
        $Priority = $demande->getPriority()->getName();
        $Canal = $demande->getCanal()->getName();

        $data[] = [
            'id' => $demande->getId(),
            'Object' => $demande->getObjet(),
            'Contact' => $contactf . ' ' . $contactl,
            // 'date' => $demande->getLast(),
            // 'date_m' => $demande->getPortable(),
            'Priority' => $Priority,
            'Canal' => $Canal,
            'Statut' => $demande->getStatut(),
        ];

        return $this->json($data);
    }

    #[Route('/demande/store', name: 'app_demande_Sh', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $demande = new Demande();


        $Contact = $request->request->get('Contact');
        $Objet = $request->request->get('Objet');
        $Gestionnaire = $request->request->get('Gestionnaire');
        $Canal = $request->request->get('Canal');
        $Raison = $request->request->get('Raison');
        $Priorite = $request->request->get('Priorite');
        $Description = $request->request->get('Description');

        $Contactid = $this->validateEntityById(Contact::class, $Contact);
        $canalId = $this->validateEntityById(CanalDemande::class, $Canal);
        $RaisonId = $this->validateEntityById(RaisonDemande::class, $Raison);
        $Prioriteid = $this->validateEntityById(PriorityDemande::class, $Priorite);

        $validator = Validation::createValidator();
        $violations = $validator->validate([
            'Contact' => $Contact,
            'Objet' => $Objet,
            'Gestionnaire' => $Gestionnaire,
            'Canal' => $Canal,
            'Raison' => $Raison,
            'Priorite' => $Priorite,
            'Description' => $Description,
        ], new Assert\Collection([
            'Contact' => new Assert\NotBlank(),
            'Objet' => new Assert\NotBlank(),
            'Gestionnaire' => new Assert\NotBlank(),
            'Canal' => new Assert\NotBlank(),
            'Priorite' => new Assert\NotBlank(),
            'Description' => new Assert\NotBlank(),
            'Raison' => new Assert\NotBlank(),
        ]));

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['errors' => $errors], 400);
        }

        $demande->setContact($Contactid);
        $demande->setObjet($Objet);
        $demande->setCanal($canalId);
        $demande->setRaison($RaisonId);
        $demande->setPriority($Prioriteid);
        $demande->setDescription($Description);

        $this->em->persist($demande);
        $this->em->flush();

        return $this->json(array('message' => 'Data stored successfully'), 201);
    }


    #[Route('/demande/edit/{id}', name: 'app_demande_U', methods: ["POST"])]
    public function Updtae(Request $request, $id): JsonResponse
    {
        $demande = $this->em->getRepository(Demande::class)->find($id);;


        $Contact = $request->request->get('Contact');
        $Objet = $request->request->get('Objet');
        $Gestionnaire = $request->request->get('Gestionnaire');
        $Canal = $request->request->get('Canal');
        $Raison = $request->request->get('Raison');
        $Priorite = $request->request->get('Priorite');
        $Description = $request->request->get('Description');

        $Contactid = $this->validateEntityById(Contact::class, $Contact);
        $canalId = $this->validateEntityById(CanalDemande::class, $Canal);
        $RaisonId = $this->validateEntityById(RaisonDemande::class, $Raison);
        $Prioriteid = $this->validateEntityById(PriorityDemande::class, $Priorite);

        $validator = Validation::createValidator();
        $violations = $validator->validate([
            'Contact' => $Contact,
            'Objet' => $Objet,
            'Gestionnaire' => $Gestionnaire,
            'Canal' => $Canal,
            'Raison' => $Raison,
            'Priorite' => $Priorite,
            'Description' => $Description,
        ], new Assert\Collection([
            'Contact' => new Assert\NotBlank(),
            'Objet' => new Assert\NotBlank(),
            'Gestionnaire' => new Assert\NotBlank(),
            'Canal' => new Assert\NotBlank(),
            'Priorite' => new Assert\NotBlank(),
            'Description' => new Assert\NotBlank(),
            'Raison' => new Assert\NotBlank(),
        ]));

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['errors' => $errors], 400);
        }

        $demande->setContact($Contactid);
        $demande->setObjet($Objet);
        $demande->setCanal($canalId);
        $demande->setRaison($RaisonId);
        $demande->setPriority($Prioriteid);
        $demande->setDescription($Description);
        $this->em->flush();

        return $this->json(array('message' => 'Data Updated successfully'), 201);
    }

    #[Route('/demande/destroy/{id}', name: 'app_demande_destroy', methods: ["DELETE"])]
    public function destroy($id): JsonResponse
    {
        $demande = $this->em->getRepository(Demande::class)->find($id);

        if (!$demande) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $this->em->remove($demande);
        $this->em->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

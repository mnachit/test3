<?php

namespace App\Controller;

use App\Entity\Planning;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

use function PHPSTORM_META\map;

#[Route('/api')]
class PlanningController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    #[Route('/planning', name: 'app_planning', methods:["GET"])]
    public function index(): JsonResponse
    {
        $plannings = $this->em->getRepository(Planning::class)->findAll();

        $data = [];

        foreach($plannings as $planning)
        {
            $data[] = [
                'id' => $planning->getId(),
                'name' => $planning->getName(),
                'date_d' => $planning->getDateD()->format('Y-m-d'),
                'date_f' => $planning->getDateF()->format('Y-m-d'),
            ];
        }
        
        return $this->json($data);
    }

    #[Route('/planning/store', name: 'app_planning_store', methods:["POST"])]
    public function store(Request $request) : JsonResponse
    {
        $planning = new Planning();

        $name = $request->request->get('name');
        $date_d = new DateTime($request->request->get('date_d')); 
        $date_f = new DateTime($request->request->get('date_f'));

        $validator = Validation::createValidator();
        $violations = $validator->validate([
            'name' => $name,
            'date_d' => $date_d,
            'date_f' => $date_f
        ], new Assert\Collection([
            'name' => new Assert\NotBlank(),
            'date_d' => new Assert\NotBlank(),
            'date_f' => new Assert\NotBlank()
        ]));

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return new JsonResponse(['errors' => $errors], 400);
        }

        $planning->setName($name);
        $planning->setDateD($date_d);
        $planning->setDateF($date_f);

        $this->em->persist($planning);
        $this->em->flush();

        return $this->json(array('message' => 'Data stored successfully'), 201);
    }
}

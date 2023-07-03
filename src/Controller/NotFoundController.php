<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotFoundController extends AbstractController
{
    /**
     * @Route("/not-found", name="not_found")
     */
    public function notFound(): JsonResponse
    {
        return new JsonResponse(['error' => 'Page not found'], Response::HTTP_NOT_FOUND);
    }
}


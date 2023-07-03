<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\CategoryProduct;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

#[Route('/api/category')]
class CategoryProductController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Get All Category Products
     *
     * This endpoint retrieves all the category products.
     *
     * @Route("/product", name="category_product", methods={"GET"})
     *
     * @OA\Get(
     *     path="/api/category/product",
     *     summary="Get all category products",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/CategoryProduct"))
     *     ),
     *     @OA\Tag(name="Category Products")
     * )
     */
    #[Route('/product', name: 'category_product', methods: ["GET"])]
    public function index(): JsonResponse
    {

        $categorys = $this->getDoctrine()->getRepository(CategoryProduct::class)->findAll();

        $data = [];

        foreach ($categorys as $category) {
            $data[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
            ];
        }
        return new JsonResponse([
            "data" => $data
        ]);
    }

    /**
     * Create a Category Product
     *
     * This endpoint creates a new category product.
     *
     * @Route("/product", name="store_category_product", methods={"POST"})
     *
     * @OA\Post(
     *     path="/api/category/product",
     *     summary="Create a category product",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category product created",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Tag(name="Category Products")
     * )
     */
    #[Route('/product', name: 'store_category_product', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $category = new CategoryProduct();

        $category->setName($request->request->get('name'));

        $this->em->persist($category);
        $this->em->flush();
        return new JsonResponse(["message" => "Ressource is stored successfully. : {$category->getId()}"]);
    }


    /**
     * Get a Category Product
     *
     * This endpoint retrieves a category product by its ID.
     *
     * @Route("/product/{id}", name="show_category_product", methods={"GET"})
     *
     * @OA\Get(
     *     path="/api/category/product/{id}",
     *     summary="Get a category product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Category ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(ref="#/components/schemas/CategoryProduct")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *     @OA\Tag(name="Category Products")
     * )
     */
    #[Route('/product/{id}', name: 'show_category_product',  methods: 'GET')]
    public function show(int $id): JsonResponse
    {
        $category = $this->getDoctrine()->getRepository(CategoryProduct::class)->find($id);

        if (!$category) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $data = [
            'id' => $category->getId(),
            'name' => $category->getName(),
        ];

        return new JsonResponse($data);
    }

    /**
     * Update a Category Product
     *
     * This endpoint updates a category product by its ID.
     *
     * @Route("/product/update/{id}", name="update_category_product", methods={"POST"})
     *
     * @OA\Post(
     *     path="/api/category/product/update/{id}",
     *     summary="Update a category product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Category ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category product updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *     @OA\Tag(name="Category Products")
     * )
     */
    #[Route('product/update/{id}', name: 'update_category_product',  methods: 'POST')]
    public function update(int $id, Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(CategoryProduct::class)->find($id);

        if (!$category) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $name = $request->request->get('Name');

        $category->setName($name);

        $entityManager->flush();

        return $this->json(['message' => "Updated successfully"]);
    }

    /**
     * Delete a Category Product
     *
     * This endpoint deletes a category product by its ID.
     *
     * @Route("/product/destroy/{id}", name="destroy_category_product", methods={"DELETE"})
     *
     * @OA\Delete(
     *     path="/api/category/product/destroy/{id}",
     *     summary="Delete a category product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Category ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category product deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *     @OA\Tag(name="Category Products")
     * )
     */
    #[Route('/product/destroy/{id}', name: 'destroy_category_product',  methods: 'DELETE')]
    public function destroy(int $id): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(CategoryProduct::class)->find($id);

        if (!$category) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $entityManager->remove($category);
        $entityManager->flush();

        return $this->json(['message' => "destroy successfully : {$category->getId()}"]);
    }
}

<?php

namespace App\Controller;

use App\Entity\CategoryProduct;
use App\Entity\ClasseTherapeutique;
use App\Entity\Dci;
use App\Entity\FormeGalenique;
use App\Entity\Gamme;
use App\Entity\Laboratory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Product;
use App\Entity\ProductTable;
use App\Entity\Sousgame;
use App\Entity\TaxeAchat;
use App\Entity\TaxeVente;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

#[Route('/api/products')]
class ProductController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function validateEntityById($entityClass, $id)
    {
        $entity = $this->em->getRepository($entityClass)->findOneBy(['id' => $id]);

        if (!$entity) {
            return $this->json(['error' => "Invalid $entityClass provided"], 400);
        }

        return $entity;
    }

    /**
     * Get all products (index_H)
     *
     * This endpoint retrieves a list of all products.
     *
     * @Route("/", name="app_product_H", methods={"GET"})
     *
     * @return JsonResponse
     * 
     * @OA\Get(
     *     path="/api/products/",
     *     summary="Returns the list of products",
     *     @OA\Response(
     *         response=200,
     *         description="Returns the list of products",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref=@Model(type=Product::class))
     *         )
     *     ),
     *     @OA\Tag(name="Products")
     * )
     */

    // here the 1st index method .

    #[Route('/history', name: 'app_product_H', methods: ["GET"])]
    public function index_H(): JsonResponse
    {
        $products = $this->em->getRepository(Product::class)->findAll();
        $data = [];
        foreach ($products as $product) {
            $categorieName = $product->getCategory()->getName();
            $FormeGaleniqueName = $product->getFormeGalenique()->getName();
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'Catégorie' => $categorieName,
                'FORME GALÉNIQUE' => $FormeGaleniqueName,
                'PPH' => $product->getPph(),
                'PPV' => $product->getPpv(),
                'CODE BARRE' => $product->getCodeBarre(),
                'statut' => $product->getStatut(),
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Get a single product
     *
     * This endpoint retrieves a single product by its ID.
     *
     * @Route("/{id}", name="app_product_show", methods={"GET"})
     *
     * @param Product $product
     * @return JsonResponse
     *
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Returns a single product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Returns a single product",
     *         @OA\JsonContent(ref=@Model(type=Product::class))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Tag(name="Products")
     * )
     */

    // Here the hsow method .
    #[Route('/{id}', name: 'app_product_show', methods: ["GET"])]
    public function show($id): JsonResponse
    {   
        $id = (int) $id;
        return $this->json($id);
        $product = $this->em->getRepository(Product::class)->find($id);

        if (!$product) {
            return $this->json(['message' => "Product with id :{$id}, doesn't exist"]);
        }

        $categorieName = $product->getCategory()->getName();
        $FormeGaleniqueName = $product->getFormeGalenique()->getName();
        $data[] = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'Catégorie' => $categorieName,
            'FORME GALÉNIQUE' => $FormeGaleniqueName,
            'PPH' => $product->getPph(),
            'PPV' => $product->getPpv(),
            'CODE BARRE' => $product->getCodeBarre(),
        ];

        return new JsonResponse($data);
    }


    // here the index method .
    #[Route('/', name: 'product_index', methods: ["GET"])]
    public function index(): JsonResponse
    {
        $repository = $this->em->getRepository(Product::class);
        $queryBuilder = $repository->createQueryBuilder('p');
        $queryBuilder->where('p.statut = :statut')
            ->setParameter('statut', 'Valide');
        $products = $queryBuilder->getQuery()->getResult();
        $data = [];
        foreach ($products as $product) {
            $categorieName = $product->getCategory()->getName();
            $FormeGaleniqueName = $product->getFormeGalenique()->getName();
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'Catégorie' => $categorieName,
                'FORME GALÉNIQUE' => $FormeGaleniqueName,
                'PPH' => $product->getPph(),
                'PPV' => $product->getPpv(),
                'CODE BARRE' => $product->getCodeBarre(),
            ];
        }

        return new JsonResponse($data);
    }

    /**
     * Create a new product
     *
     * This endpoint creates a new product.
     *
     * @Route("/", name="app_product_create", methods={"POST"})
     *
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/products/",
     *     summary="Creates a new product",
     *     @OA\RequestBody(
     *         description="Product data",
     *         required=true,
     *         @OA\JsonContent(ref=@Model(type=Product::class))
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(ref=@Model(type=Product::class))
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request payload"
     *     ),
     *     @OA\Tag(name="Products")
     * )
     */
    // here the store method .
    #[Route('/store', name: 'product_store', methods: ["POST"])]
    public function store(Request $request): JsonResponse
    {
        $Products = new Product();

        $name = $request->request->get('nom');
        $code_de_barre = $request->request->get('code_de_barre');
        $code_de_barre2 = $request->request->get('code_de_barre2');
        $pph = $request->request->get('pph');
        $ppv = $request->request->get('ppv');
        $Est_remboursable = $request->request->get('Est_remboursable');
        $Base_de_remboursement = $request->request->get('Base_de_remboursement');
        $need_prescription = $request->request->get('need_prescription');
        $market_product = $request->request->get('market_product');

        $category = $request->request->get('categorie');
        $classe_therapeutique = $request->request->get('classe_therapeutique');
        $dci = $request->request->get('dci');
        $forme_galenique = $request->request->get('forme_galenique');
        $gamme = $request->request->get('gamme');
        $laboratoire = $request->request->get('laboratoire');
        $produit_tableau = $request->request->get('produit_tableau');
        $sous_gamme = $request->request->get('sous_gamme');
        $taxe_sur_achat = $request->request->get('taxe_sur_achat');
        $taxe_sur_vente = $request->request->get('taxe_sur_vente');


        $category_id = $this->validateEntityById(CategoryProduct::class, $category);
        $classe_therapeutique_id = $this->validateEntityById(ClasseTherapeutique::class, $classe_therapeutique);
        $dci_id = $this->validateEntityById(Dci::class, $dci);
        $forme_galenique_id = $this->validateEntityById(FormeGalenique::class, $forme_galenique);
        $gamme_id = $this->validateEntityById(Gamme::class, $gamme);
        $laboratoire_id = $this->validateEntityById(Laboratory::class, $laboratoire);
        $produit_tableau_id = $this->validateEntityById(ProductTable::class, $produit_tableau);
        $sous_gamme_id = $this->validateEntityById(Sousgame::class, $sous_gamme);
        $taxe_sur_achat_id = $this->validateEntityById(TaxeAchat::class, $taxe_sur_achat);
        $taxe_sur_vente_id = $this->validateEntityById(TaxeVente::class, $taxe_sur_vente);

        $validator = Validation::createValidator();

        $constraints = new Assert\Collection([
            'nom' => new Assert\NotBlank(),
            'need_prescription' => new Assert\NotBlank(),
            'market_product' => new Assert\NotBlank(),
            'code_de_barre' => new Assert\NotBlank(),
            'code_de_barre2' => new Assert\NotBlank(),
            'pph' => new Assert\NotBlank(),
            'ppv' => new Assert\NotBlank(),
            'Est_remboursable' => new Assert\NotBlank(),
            'categorie' => new Assert\NotBlank(),
            'sous_gamme' => new Assert\NotBlank(),
            'classe_therapeutique' => new Assert\NotBlank(),
            'forme_galenique' => new Assert\NotBlank(),
            'laboratoire' => new Assert\NotBlank(),
            'produit_tableau' => new Assert\NotBlank(),
            'dci' => new Assert\NotBlank(),
            'taxe_sur_achat' => new Assert\NotBlank(),
            'taxe_sur_vente' => new Assert\NotBlank(),
            'gamme' => new Assert\NotBlank(),
        ]);

        $requestData = $request->request->all();
        $test = $requestData['Base_de_remboursement'] ?? null;
        unset($requestData['Base_de_remboursement']);

        $violations = $validator->validate($requestData, $constraints);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $propertyPath = $violation->getPropertyPath();
                $message = $violation->getMessage();
                $errors[$propertyPath] = $message;
            }

            return new JsonResponse(['errors' => $errors], 400);
        }

        if (!($Est_remboursable === "Oui")) {
            $validator1 = Validation::createValidator();

            $constraints1 = new Assert\Collection([
                'Base_de_remboursement' => new Assert\NotBlank(),
            ]);

            $violations1 = $validator1->validate(['Base_de_remboursement' => $Base_de_remboursement], $constraints1);

            if (count($violations1) > 0) {
                $errors1 = [];
                foreach ($violations1 as $violation1) {
                    $propertyPath1 = $violation1->getPropertyPath();
                    $message1 = $violation1->getMessage();
                    $errors1[$propertyPath1] = $message1;
                }

                return new JsonResponse(['errors' => $errors1], 400);
            }
        }

        $Products->setName($name);
        $Products->setCodebarre($code_de_barre);
        $Products->setCodeBarre2($code_de_barre2);
        $Products->setPph($pph);
        $Products->setPpv($ppv);
        $Products->setNeedPrescription($need_prescription);
        $Products->setMarketProduct($market_product);
        $Products->setDateCreated(new \DateTime());
        $Products->setDateModified(new \DateTime());
        if ($Est_remboursable === "Oui") {
            $Products->setBaseRemboursement($Base_de_remboursement);
        }

        $Products->setCategory($category_id);
        $Products->setSousgame($sous_gamme_id);
        $Products->setClasseTherapeutique($classe_therapeutique_id);
        $Products->setFormeGalenique($forme_galenique_id);
        $Products->setLaboratory($laboratoire_id);
        $Products->setProduitTableau($produit_tableau_id);
        $Products->setDci($dci_id);
        $Products->setTaxeAchat($taxe_sur_achat_id);
        $Products->setTaxeVente($taxe_sur_vente_id);
        $Products->setGamme($gamme_id);

        $this->em->persist($Products);
        $this->em->flush();
        return new JsonResponse(['message' => 'Data stored successfully']);
    }

    /**
     * Update an existing product
     *
     * This endpoint updates an existing product by its ID.
     *
     * @Route("/{id}", name="app_product_update", methods={"PUT"})
     *
     * @param Product $product
     * @return JsonResponse
     *
     * @OA\Put(
     *     path="/api/products/{id}",
     *     summary="Updates an existing product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         description="Updated product data",
     *         required=true,
     *         @OA\JsonContent(ref=@Model(type=Product::class))
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(ref=@Model(type=Product::class))
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request payload"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Tag(name="Products")
     * )
     */

    // here update method 
    #[Route('/update/{id}', name: 'product_update', methods: ["POST", "PUT", "PATCH"])]
    function update(Request $request, $id): JsonResponse
    {
        $Products = $this->em->getRepository(Product::class)->find($id);

        if (!$Products) {
            return new JsonResponse(['error' => 'Product not found'], 404);
        }

        $name = $request->request->get('nom');
        $code_de_barre = $request->request->get('code_de_barre');
        $code_de_barre2 = $request->request->get('code_de_barre2');
        $pph = $request->request->get('pph');
        $ppv = $request->request->get('ppv');
        $Est_remboursable = $request->request->get('Est_remboursable');
        $Base_de_remboursement = $request->request->get('Base_de_remboursement');
        $need_prescription = $request->request->get('need_prescription');
        $market_product = $request->request->get('market_product');

        $category = $request->request->get('categorie');
        $classe_therapeutique = $request->request->get('classe_therapeutique');
        $dci = $request->request->get('dci');
        $forme_galenique = $request->request->get('forme_galenique');
        $gamme = $request->request->get('gamme');
        $laboratoire = $request->request->get('laboratoire');
        $produit_tableau = $request->request->get('produit_tableau');
        $sous_gamme = $request->request->get('sous_gamme');
        $taxe_sur_achat = $request->request->get('taxe_sur_achat');
        $taxe_sur_vente = $request->request->get('taxe_sur_vente');

        $category_id = $this->validateEntityById(CategoryProduct::class, $category);
        $classe_therapeutique_id = $this->validateEntityById(ClasseTherapeutique::class, $classe_therapeutique);
        $dci_id = $this->validateEntityById(Dci::class, $dci);
        $forme_galenique_id = $this->validateEntityById(FormeGalenique::class, $forme_galenique);
        $gamme_id = $this->validateEntityById(Gamme::class, $gamme);
        $laboratoire_id = $this->validateEntityById(Laboratory::class, $laboratoire);
        $produit_tableau_id = $this->validateEntityById(ProductTable::class, $produit_tableau);
        $sous_gamme_id = $this->validateEntityById(Sousgame::class, $sous_gamme);
        $taxe_sur_achat_id = $this->validateEntityById(TaxeAchat::class, $taxe_sur_achat);
        $taxe_sur_vente_id = $this->validateEntityById(TaxeVente::class, $taxe_sur_vente);

        $validator = Validation::createValidator();

        $constraints = new Assert\Collection([
            'nom' => new Assert\NotBlank(),
            'need_prescription' => new Assert\NotBlank(),
            'market_product' => new Assert\NotBlank(),
            'code_de_barre' => new Assert\NotBlank(),
            'code_de_barre2' => new Assert\NotBlank(),
            'pph' => new Assert\NotBlank(),
            'ppv' => new Assert\NotBlank(),
            'Est_remboursable' => new Assert\NotBlank(),
            'categorie' => new Assert\NotBlank(),
            'sous_gamme' => new Assert\NotBlank(),
            'classe_therapeutique' => new Assert\NotBlank(),
            'forme_galenique' => new Assert\NotBlank(),
            'laboratoire' => new Assert\NotBlank(),
            'produit_tableau' => new Assert\NotBlank(),
            'dci' => new Assert\NotBlank(),
            'taxe_sur_achat' => new Assert\NotBlank(),
            'taxe_sur_vente' => new Assert\NotBlank(),
            'gamme' => new Assert\NotBlank(),

        ]);

        $requestData = $request->request->all();
        $test = $requestData['Base_de_remboursement'] ?? null;
        unset($requestData['Base_de_remboursement']);

        $violations = $validator->validate($requestData, $constraints);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $propertyPath = $violation->getPropertyPath();
                $message = $violation->getMessage();
                $errors[$propertyPath] = $message;
            }

            return new JsonResponse(['errors' => $errors], 400);
        }

        if (!($Est_remboursable === "Oui")) {
            $validator1 = Validation::createValidator();

            $constraints1 = new Assert\Collection([
                'Base_de_remboursement' => new Assert\NotBlank(),
            ]);

            $violations1 = $validator1->validate(['Base_de_remboursement' => $Base_de_remboursement], $constraints1);

            if (count($violations1) > 0) {
                $errors1 = [];
                foreach ($violations1 as $violation1) {
                    $propertyPath1 = $violation1->getPropertyPath();
                    $message1 = $violation1->getMessage();
                    $errors1[$propertyPath1] = $message1;
                }

                return new JsonResponse(['errors' => $errors1], 400);
            }
        }

        $Products->setName($name);
        $Products->setCodebarre($code_de_barre);
        $Products->setCodeBarre2($code_de_barre2);
        $Products->setPph($pph);
        $Products->setPpv($ppv);
        $Products->setNeedPrescription($need_prescription);
        $Products->setMarketProduct($market_product);
        $Products->setDateCreated(new \DateTime());
        $Products->setDateModified(new \DateTime());
        if ($Est_remboursable === "Oui") {
            $Products->setBaseRemboursement($Base_de_remboursement);
        }

        $Products->setCategory($category_id);
        $Products->setSousgame($sous_gamme_id);
        $Products->setClasseTherapeutique($classe_therapeutique_id);
        $Products->setFormeGalenique($forme_galenique_id);
        $Products->setLaboratory($laboratoire_id);
        $Products->setProduitTableau($produit_tableau_id);
        $Products->setDci($dci_id);
        $Products->setTaxeAchat($taxe_sur_achat_id);
        $Products->setTaxeVente($taxe_sur_vente_id);
        $Products->setGamme($gamme_id);


        $this->em->flush();
        return $this->json(['message' => "Ressource updated successfully"]);
    }

    /**
     * Delete a product
     *
     * This endpoint deletes a product by its ID.
     *
     * @Route("/{id}", name="app_product_delete", methods={"DELETE"})
     *
     * @param Product $product
     * @return JsonResponse
     *
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Deletes a product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Product deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Tag(name="Products")
     * )
     */

    #[Route('/destroy/{id}', name: 'product_delete', methods: ["DELETE"])]
    public function destroy(int $id): JsonResponse
    {
        $products = $this->em->getRepository(Product::class)->find($id);

        if (!$products) {
            return $this->json(['message' => "ID doesn't exist"]);
        }

        $this->em->remove($products);
        $this->em->flush();

        return $this->json(['message' => "destroy successfully"]);
    }
}

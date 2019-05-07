<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

use App\Repository\ProduitsRepository;
use App\Entity\Produits;
use App\Entity\Categories;
use App\Validators\Validator;

class ProduitsController extends GenericController
{
    private $repository;

    public function __construct(EntityManagerInterface $em, 
                                Validator $validator)
    {
        parent::__construct($em, $validator);
        $this->repository = $em->getRepository(Produits::class);
    }

    
    /**
     * Retrieves a collection of produits resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves a collection of produit resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Produits::class))
     *     )
     * )
     * @SWG\Parameter(
     *     name="keyword",
     *     in="query",
     *     description="Sort criterion",
     *     type="string",
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     description="Order criterion",
     *     type="string",
     * )
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     description="Page number",
     *     type="integer",
     * )
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     description="Page number",
     *     type="integer",
     * )
     * @SWG\Tag(name="produits")
     * @Security(name="Bearer")
     *
     * @Rest\Get("/produits")
     * @Rest\QueryParam(
     *     name="keyword",
     *     nullable=true,
     *     description="The keyword to search for."
     * )
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="asc",
     *     description="Sort order (asc or desc)"
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="15",
     *     description="Max number of movies per page."
     * )
     * @Rest\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="1",
     *     description="The pagination offset"
     * )
     * @Rest\View(populateDefaultVars=false, serializerEnableMaxDepthChecks=true)
     */
    public function listProduits(ParamFetcherInterface $paramFetcher)//: array
    {
        $pager = $this->repository->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );
        // var_dump($paramFetcher->get('offset'));exit;

        return [
            "totalItems" => $pager->getNbResults(),
            "currentPage" => $pager->getCurrentPage(),
            "items" => $pager->getCurrentPageResults()
        ];
    }
    
    /**
     * Retrieves an Produit resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves a Produit resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Produits::class))
     *     )
     * )
	 * @SWG\Parameter(
	 *     name="id",
	 * 	   in="path",
	 * 	   required=true,
	 * 	   type="integer"
	 * )
     * @SWG\Tag(name="produits")
     * @Security(name="Bearer")
     *
     * 
     * @Rest\Get(
     *     path="/produits/{id}",
     *     name="app_produit_show",
     *     requirements = {"id"="\d+"}
     * )
     * 
     * @Rest\View(populateDefaultVars=false, serializerEnableMaxDepthChecks=true)
     */
    public function getProduit(Produits $produit) : Produits
    {
        return $produit;
    }

    /**
     * Creates an Produits resource
     * 
     * @SWG\Response(
     *     response=200,
     *     description="The created Produit resource",
     *     @Model(type=Produits::class)
     * )
     * @SWG\Parameter(
     *     name="produit",
     *     in="body",
     *     @Model(type=Produits::class)
     * )
     * @SWG\Tag(name="produits")
     * @Security(name="Bearer")
     *
     * @Rest\Post(
     *         path = "/produits",
     *         name = "api_produit_create"
     * )
     * @Rest\View(populateDefaultVars=false, StatusCode = 201)
     * @ParamConverter("produit", class="App\Entity\Produits", converter="fos_rest.request_body")
     */
    public function createProduit(Produits $produit, ConstraintViolationList $violations): Produits
    {
        $this->dataValidator->validate($violations);

        $categorie = $produit->getCategorie();

        $catRepo = $this->getDoctrine()->getRepository(Categories::class);
        $myCat = $catRepo->findOrCreate($categorie);
        if(is_null($myCat->getNom())){
            throw $this->createNotFoundException('La categorie (id=' . $myCat->getId() . ') n\'existe pas');
        };
        $produit->setCategorie($myCat);

        $this->em->persist($produit);
        $this->em->flush();

        return $produit;
    }

    /**
     * Replaces Produit resource
     * @SWG\Response(
     *     response=200,
     *     description="The updated Produit resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Produits::class))
     *     )
     * )
	 * @SWG\Parameter(
	 *      name="id",
	 * 	    in="path",
	 * 	    required=true,
	 * 	    type="integer"
	 * 	)
     * @SWG\Parameter(
     *     name="newProduit",
     *     in="body",
     *     @Model(type=Produits::class)
     * )
     * @SWG\Tag(name="produits")
     * @Security(name="Bearer")
     *
     *   
     * @Rest\Put(
     *     path="/produits/{id}",
     *     name="app_produit_update"
     * )
     * @Rest\View(populateDefaultVars=false)
     * @ParamConverter("newProduit", class="App\Entity\Produits", converter="fos_rest.request_body")
     */
    public function putProduit(Produits $produit, Produits $newProduit, ConstraintViolationList $violations): Produits
    {
        $this->dataValidator->validate($violations);

        $produit = $this->repository->update($produit, $newProduit);
        
        $this->em->persist($produit);
        $this->em->flush();
        
        return $produit;
    }

    /**
     * Removes a Produits resource
     * @SWG\Response(
     *     response=202,
     *     description="Removes a produit resource"
     * )
	 * @SWG\Parameter(
	 *      name="id",
	 * 	    in="path",
	 * 	    required=true,
	 * 	    type="integer"
	 * 	)
     * @SWG\Tag(name="produits")
     * @Security(name="Bearer")
     * @Rest\Delete(
     *     path="/produits/{id}",
     *     name="app_produit_delete"
     * )
     * 
     * @Rest\View(
     *      populateDefaultVars=false,
     *      StatusCode = 202
     * )
     */
    public function deleteProduit(Produits $produit)
    {
        $this->em->remove($produit);
        $this->em->flush();
    }
}

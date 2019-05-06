<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;

use App\Entity\Categories;
use App\Validators\Validator;

class CategoriesController extends GenericController
{
    private $repository;

    public function __construct(EntityManagerInterface $em, 
                                Validator $validator)
    {
        parent::__construct($em, $validator);
        $this->repository = $em->getRepository(Categories::class);
    }

    // *     requirements="[a-zA-Z0-9]",
    /**
     * Retrieves a collection of Categories resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves a collection of Categories resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Categories::class, groups={"full"}))
     *     )
     * )
     * @SWG\Tag(name="categories")
     * @Security(name="Bearer")
     *
     * @Rest\Get("/categories")
     * @Rest\View(populateDefaultVars=false)
     */
    public function listCategoriess()//: array
    {
        return $this->repository->findAll();
    }


    /**
     * Retrieves an Categories resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves an Categories resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Categories::class))
     *     )
     * )
     * @SWG\Tag(name="categories")
     * @Security(name="Bearer")
     * 
     * @Rest\Get(
     *     path="/categories/{id}",
     *     name="app_categorie_show",
     *     requirements = {"id"="\d+"}
     * )
     * 
     * @Rest\View(populateDefaultVars=false)
     */
    public function getCategories(Categories $categorie) : Categories
    {
        return $categorie;
    }
}

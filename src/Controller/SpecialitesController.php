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

use App\Repository\SpecialitesRepository;
use App\Entity\Specialites;
use App\Validators\Validator;

class SpecialitesController extends  GenericController
{
    private $repository;

    public function __construct(SpecialitesRepository $repository, 
                                EntityManagerInterface $em, 
                                Validator $validator)
    {
        parent::__construct($em, $validator);
        $this->repository = $repository;
    }

    /**
     * Retrieves a collection of Specialites resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves a collection of Specialites resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Specialites::class))
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
     * @SWG\Tag(name="specialites")
     * @Security(name="Bearer")
     *
     * @Rest\Get(
     *     path="/specialites",
     *     name="app_specialite_list"
     * )
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
     * @Rest\View(populateDefaultVars=false)
     */
    public function listSpecialitess(ParamFetcherInterface $paramFetcher)//: array
    {
        $pager = $this->repository->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );

        return [
            "totalItems" => $pager->getNbResults(),
            "currentPage" => $pager->getCurrentPage(),
            "items" => $pager->getCurrentPageResults()
        ];
    }


    /**
     * Retrieves an Specialites resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves an Specialites resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Specialites::class))
     *     )
     * )
	 * @SWG\Parameter(
	 *     name="id",
	 * 	   in="path",
	 * 	   required=true,
	 * 	   type="integer"
	 * )
     * @SWG\Tag(name="specialites")
     * @Security(name="Bearer")
     * 
     * @Rest\Get(
     *     path="/specialites/{id}",
     *     name="app_specialite_show",
     *     requirements = {"id"="\d+"}
     * )
     * 
     * @Rest\View(populateDefaultVars=false)
     */
    public function getSpecialite(Specialites $specialite) : Specialites
    {
        return $specialite;
    }

    /**
     * Update Specialites resource
     * @SWG\Response(
     *     response=200,
     *     description="The updated Specialites resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Specialites::class))
     *     )
     * )
	 * @SWG\Parameter(
	 *      name="id",
	 * 	    in="path",
	 * 	    required=true,
	 * 	    type="integer"
	 * 	)
     * @SWG\Parameter(
     *     name="newSpecialites",
     *     in="body",
     *     @Model(type=Specialites::class)
     * )
     * @SWG\Tag(name="specialites")
     * @Security(name="Bearer")
     * @Rest\Put(
     *     path="/specialites/{id}",
     *     name="app_specialite_update"
     * )
     * @Rest\View(populateDefaultVars=false)
     * @ParamConverter("newSpecialites", class="App\Entity\Specialites", converter="fos_rest.request_body")
     */
    public function putSpecialites(Specialites $specialite, Specialites $newSpecialites, ConstraintViolationList $violations): Specialites
    {
        $this->dataValidator->validate($violations);

        $specialite->setLibelle($newSpecialites->getLibelle());

        $this->em->persist($specialite);
        $this->em->flush();
        
        return $specialite;
    }
}

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

use App\Repository\ProfessionelsRepository;
use App\Entity\Professionels;
use App\Validators\Validator;

class ProfessionelsController extends GenericController
{
    private $repository;

    public function __construct(ProfessionelsRepository $repository, 
                                EntityManagerInterface $em, 
                                Validator $validator)
    {
        parent::__construct($em, $validator);
        $this->repository = $repository;
    }
    

    // *     requirements="[a-zA-Z0-9]",
    /**
     * Retrieves a collection of professionels resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves a collection of professionel resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Professionels::class))
     *     )
     * )
     *     @SWG\Parameter(
     *         name="keyword",
     *         in="query",
     *         description="Sort criterion",
     *         type="string",
     *     )
     *     @SWG\Parameter(
     *         name="order",
     *         in="query",
     *         description="Order criterion",
     *         type="string",
     *     )
     *     @SWG\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Page number",
     *         type="integer",
     *     )
     *     @SWG\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Page number",
     *         type="integer",
     *     )
     * @SWG\Tag(name="professionels")
     * @Security(name="Bearer")
     *
     * @Rest\Get("/professionels")
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
    public function listProfessionels(ParamFetcherInterface $paramFetcher)//: array
    {
        $pager = $this->repository->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );
        
        return $pager->getCurrentPageResults();
    }
    
    /**
     * Retrieves an Professionel resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves a Professionel resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Professionels::class))
     *     )
     * )
     * @SWG\Tag(name="professionels")
     * @Security(name="Bearer")
     * 
     * @Rest\Get(
     *     path="/professionels/{id}",
     *     name="app_professionel_show",
     *     requirements = {"id"="\d+"}
     * )
     * 
     * @Rest\View(populateDefaultVars=false)
     */
    public function getProfessionel(Professionels $professionel) : Professionels
    {
        return $professionel;
    }

    /**
     * Creates an Professionels resource
     * 
     * @SWG\Response(
     *     response=200,
     *     description="The created Professionel resource",
     *     @Model(type=Professionels::class)
     * )
     * @SWG\Parameter(
     *     name="professionel",
     *     in="body",
     *     @Model(type=Professionels::class)
     * )
     * @SWG\Tag(name="professionels")
     * @Security(name="Bearer")
     * @Rest\Post(
     *         path = "/professionels",
     *         name = "api_professionel_create"
     * )
     * @Rest\View(populateDefaultVars=false, StatusCode = 201)
     * @ParamConverter("professionel", class="App\Entity\Professionels", converter="fos_rest.request_body")
     */
    public function createProfessionel(Professionels $professionel, ConstraintViolationList $violations): Professionels
    {
        $this->dataValidator->validate($violations);

        $this->em->persist($professionel);
        $this->em->flush();

        return $professionel;
    }

    /**
     * Replaces Professionnel resource
     * @SWG\Response(
     *     response=200,
     *     description="The updated Professionnel resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Professionels::class))
     *     )
     * )
	 * @SWG\Parameter(
	 *      name="id",
	 * 	    in="path",
	 * 	    required=true,
	 * 	    type="integer"
	 * 	)
     * @SWG\Parameter(
     *     name="newProfessionel",
     *     in="body",
     *     @Model(type=Professionels::class)
     * )
     * @SWG\Tag(name="professionels")
     * @Security(name="Bearer")
     * 
     * @Rest\Put(
     *     path="/professionels/{id}",
     *     name="app_professionnel_update"
     * )
     * @Rest\View(populateDefaultVars=false)
     * @ParamConverter("newProfessionel", class="App\Entity\Professionels", converter="fos_rest.request_body")
     */
    public function putProfessionnel(Professionels $professionels, Professionels $newProfessionel, ConstraintViolationList $violations): Professionels
    {
        // $this->dataValidator->validate($violations);
        var_dump($professionels);
        var_dump($newProfessionel);
        exit;


        // $professionels->setTauxHoraire($newProfessionel->getTauxHoraire());
        // $professionels->setStatus($newProfessionel->getStatus());
        // $professionels->setDescription($newProfessionel->getDescription());
        // $professionels->setExperience($newProfessionel->getExperience());

        // $this->em->persist($professionels);
        // $this->em->flush();
        
        return $professionels;
    }

    /**
     * Removes a Professionels resource
     * @SWG\Response(
     *     response=202,
     *     description="Removes a professionel resource"
     * )
	 * @SWG\Parameter(
	 *      name="id",
	 * 	    in="path",
	 * 	    required=true,
	 * 	    type="integer"
	 * 	)
     * @SWG\Tag(name="professionels")
     * @Security(name="Bearer")
     * @Rest\Delete(
     *     path="/professionels/{id}",
     *     name="app_professionel_delete"
     * )
     * 
     * @Rest\View(
     *      populateDefaultVars=false,
     *      StatusCode = 202
     * )
     */
    public function deleteProfessionel(Professionels $professionel)
    {
        // $em = $this->getDoctrine()->getManager();

        $this->em->remove($professionel);
        $this->em->flush();
    }
}

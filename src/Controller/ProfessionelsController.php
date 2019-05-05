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
use App\Entity\Competences;
use App\Entity\Specialites;
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
	 * @SWG\Parameter(
	 *     name="id",
	 * 	   in="path",
	 * 	   required=true,
	 * 	   type="integer"
	 * )
     * @SWG\Tag(name="professionels")
     * @Security(name="Bearer")
     *
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
     *
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
     *   
     * @Rest\Put(
     *     path="/professionels/{id}",
     *     name="app_professionnel_update"
     * )
     * @Rest\View(populateDefaultVars=false)
     * @ParamConverter("newProfessionel", class="App\Entity\Professionels", converter="fos_rest.request_body")
     */
    public function putProfessionnel(Professionels $professionel, Professionels $newProfessionel, ConstraintViolationList $violations): Professionels
    {
        $this->dataValidator->validate($violations);
        
        $compRepo = $this->getDoctrine()->getRepository(Competences::class);
        if(!is_null($newProfessionel->getCompetences())){
            foreach($newProfessionel->getCompetences() as $competence){
                $myComp = $compRepo->findOrCreate($competence);
                $professionel->addCompetence($myComp);
            }
        }
        
        $specRepo = $this->getDoctrine()->getRepository(Specialites::class);
        if(!is_null($newProfessionel->getSpecialites())){
            foreach($newProfessionel->getSpecialites() as $specialite){
                $mySpec = $specRepo->findOrCreate($specialite);
                $professionel->addSpecialite($mySpec);
            }
        }

        $professionel = $this->repository->update($professionel, $newProfessionel);
        
        $this->em->persist($professionel);
        $this->em->flush();
        
        return $professionel;
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
        $this->em->remove($professionel);
        $this->em->flush();
    }

    /**
     * Add a Competences resource to a Professionels resource
     * @SWG\Response(
     *     response=200,
     *     description="The added Competences resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Competences::class))
     *     )
     * )
	 * @SWG\Parameter(
	 *      name="id",
	 * 	    in="path",
	 * 	    required=true,
	 * 	    type="integer"
	 * 	)
     * @SWG\Parameter(
     *     name="newCompetence",
     *     in="body",
     *     description="Competences Resource to add",
     *     @Model(type=Competences::class)
     * )
     * @SWG\Tag(name="professionels")
     * @Security(name="Bearer")
     *
     *   
     * @Rest\Put(
     *     path="/professionels/{id}/competence",
     *     name="app_professionnel_add_competence"
     * )
     * @Rest\View(populateDefaultVars=false)
     * @ParamConverter("newCompetence", class="App\Entity\Competences", converter="fos_rest.request_body")
     */
    public function addCompetence(Professionels $professionel, Competences $newCompetence)
    {
        $compRepo = $this->getDoctrine()->getRepository(Competences::class);
        $competence = $compRepo->findOrCreate($newCompetence);
        
        $professionel->addCompetence($competence);

        $this->em->persist($professionel);
        $this->em->flush();
        
        return $competence;
    }

    /**
     * Add a Specialites resource to a Professionels resource
     * @SWG\Response(
     *     response=200,
     *     description="The added Specialites resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Specialites::class))
     *     )
     * )
	 * @SWG\Parameter(
	 *     name="id",
	 * 	   in="path",
	 * 	   required=true,
     *     description="Entity id to update",
	 * 	   type="integer"
	 * )
     * @SWG\Parameter(
     *     name="newSpecialite",
     *     in="body",
     *     description="Specialites Resource to add",
     *     @Model(type=Specialites::class)
     * )
     * @SWG\Tag(name="professionels")
     * @Security(name="Bearer")
     *
     *   
     * @Rest\Put(
     *     path="/professionels/{id}/specialite",
     *     name="app_professionnel_add_specialite"
     * )
     * @Rest\View(populateDefaultVars=false)
     * @ParamConverter("newSpecialite", class="App\Entity\Specialites", converter="fos_rest.request_body")
     */
    public function addSpecialite(Professionels $professionel, Specialites $newSpecialite)
    {
        $specRepo = $this->getDoctrine()->getRepository(Specialites::class);
        $specialite = $specRepo->findOrCreate($newSpecialite);
        
        $professionel->addSpecialite($specialite);

        $this->em->persist($professionel);
        $this->em->flush();
        
        return $specialite;
    }
}

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

use App\Repository\ProfessionnelsRepository;
use App\Entity\Professionnels;
use App\Entity\Competences;
use App\Entity\Specialites;
use App\Validators\Validator;

class ProfessionnelsController extends GenericController
{
    private $repository;

    public function __construct(ProfessionnelsRepository $repository, 
                                EntityManagerInterface $em, 
                                Validator $validator)
    {
        parent::__construct($em, $validator);
        $this->repository = $repository;
    }

    
    /**
     * Retrieves a collection of professionnels resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves a collection of professionnel resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Professionnels::class))
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
     * @SWG\Tag(name="professionnels")
     * @Security(name="Bearer")
     *
     * @Rest\Get("/professionnels")
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
    public function listProfessionnels(ParamFetcherInterface $paramFetcher)//: array
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
     * Retrieves an Professionnel resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves a Professionnel resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Professionnels::class))
     *     )
     * )
	 * @SWG\Parameter(
	 *     name="id",
	 * 	   in="path",
	 * 	   required=true,
	 * 	   type="integer"
	 * )
     * @SWG\Tag(name="professionnels")
     * @Security(name="Bearer")
     *
     * 
     * @Rest\Get(
     *     path="/professionnels/{id}",
     *     name="app_professionnel_show",
     *     requirements = {"id"="\d+"}
     * )
     * 
     * @Rest\View(populateDefaultVars=false)
     */
    public function getProfessionnel(Professionnels $professionnel) : Professionnels
    {
        return $professionnel;
    }

    /**
     * Creates an Professionnels resource
     * 
     * @SWG\Response(
     *     response=200,
     *     description="The created Professionnel resource",
     *     @Model(type=Professionnels::class)
     * )
     * @SWG\Parameter(
     *     name="professionnel",
     *     in="body",
     *     @Model(type=Professionnels::class)
     * )
     * @SWG\Tag(name="professionnels")
     * @Security(name="Bearer")
     *
     * @Rest\Post(
     *         path = "/professionnels",
     *         name = "api_professionnel_create"
     * )
     * @Rest\View(populateDefaultVars=false, StatusCode = 201)
     * @ParamConverter("professionnel", class="App\Entity\Professionnels", converter="fos_rest.request_body")
     */
    public function createProfessionnel(Professionnels $professionnel, ConstraintViolationList $violations): Professionnels
    {
        $this->dataValidator->validate($violations);

        $this->em->persist($professionnel);
        $this->em->flush();

        return $professionnel;
    }

    /**
     * Replaces Professionnel resource
     * @SWG\Response(
     *     response=200,
     *     description="The updated Professionnel resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Professionnels::class))
     *     )
     * )
	 * @SWG\Parameter(
	 *      name="id",
	 * 	    in="path",
	 * 	    required=true,
	 * 	    type="integer"
	 * 	)
     * @SWG\Parameter(
     *     name="newProfessionnel",
     *     in="body",
     *     @Model(type=Professionnels::class)
     * )
     * @SWG\Tag(name="professionnels")
     * @Security(name="Bearer")
     *
     *   
     * @Rest\Put(
     *     path="/professionnels/{id}",
     *     name="app_professionnel_update"
     * )
     * @Rest\View(populateDefaultVars=false)
     * @ParamConverter("newProfessionnel", class="App\Entity\Professionnels", converter="fos_rest.request_body")
     */
    public function putProfessionnel(Professionnels $professionnel, Professionnels $newProfessionnel, ConstraintViolationList $violations): Professionnels
    {
        $this->dataValidator->validate($violations);
        
        $compRepo = $this->getDoctrine()->getRepository(Competences::class);
        if(!is_null($newProfessionnel->getCompetences())){
            foreach($newProfessionnel->getCompetences() as $competence){
                $myComp = $compRepo->findOrCreate($competence);
                $professionnel->addCompetence($myComp);
            }
        }
        
        $specRepo = $this->getDoctrine()->getRepository(Specialites::class);
        if(!is_null($newProfessionnel->getSpecialites())){
            foreach($newProfessionnel->getSpecialites() as $specialite){
                $mySpec = $specRepo->findOrCreate($specialite);
                $professionnel->addSpecialite($mySpec);
            }
        }

        $professionnel = $this->repository->update($professionnel, $newProfessionnel);
        
        $this->em->persist($professionnel);
        $this->em->flush();
        
        return $professionnel;
    }

    /**
     * Removes a Professionnels resource
     * @SWG\Response(
     *     response=202,
     *     description="Removes a professionnel resource"
     * )
	 * @SWG\Parameter(
	 *      name="id",
	 * 	    in="path",
	 * 	    required=true,
	 * 	    type="integer"
	 * 	)
     * @SWG\Tag(name="professionnels")
     * @Security(name="Bearer")
     * @Rest\Delete(
     *     path="/professionnels/{id}",
     *     name="app_professionnel_delete"
     * )
     * 
     * @Rest\View(
     *      populateDefaultVars=false,
     *      StatusCode = 202
     * )
     */
    public function deleteProfessionnel(Professionnels $professionnel)
    {
        $this->em->remove($professionnel);
        $this->em->flush();
    }

    /**
     * Add a Competences resource to a Professionnels resource
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
     * @SWG\Tag(name="professionnels")
     * @Security(name="Bearer")
     *
     *   
     * @Rest\Put(
     *     path="/professionnels/{id}/competence",
     *     name="app_professionnel_add_competence"
     * )
     * @Rest\View(populateDefaultVars=false)
     * @ParamConverter("newCompetence", class="App\Entity\Competences", converter="fos_rest.request_body")
     */
    public function addCompetence(Professionnels $professionnel, Competences $newCompetence)
    {
        $compRepo = $this->getDoctrine()->getRepository(Competences::class);
        $competence = $compRepo->findOrCreate($newCompetence);
        
        $professionnel->addCompetence($competence);

        $this->em->persist($professionnel);
        $this->em->flush();
        
        return $competence;
    }

    /**
     * Add a Specialites resource to a Professionnels resource
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
     * @SWG\Tag(name="professionnels")
     * @Security(name="Bearer")
     *
     *   
     * @Rest\Put(
     *     path="/professionnels/{id}/specialite",
     *     name="app_professionnel_add_specialite"
     * )
     * @Rest\View(populateDefaultVars=false)
     * @ParamConverter("newSpecialite", class="App\Entity\Specialites", converter="fos_rest.request_body")
     */
    public function addSpecialite(Professionnels $professionnel, Specialites $newSpecialite)
    {
        $specRepo = $this->getDoctrine()->getRepository(Specialites::class);
        $specialite = $specRepo->findOrCreate($newSpecialite);
        
        $professionnel->addSpecialite($specialite);

        $this->em->persist($professionnel);
        $this->em->flush();
        
        return $specialite;
    }
}

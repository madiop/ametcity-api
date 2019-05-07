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

use App\Entity\Entreprises;
use App\Entity\Prestations;
use App\Entity\Specialites;
use App\Validators\Validator;

class EntreprisesController extends GenericController
{
    private $repository;

    public function __construct(EntityManagerInterface $em, 
                                Validator $validator)
    {
        parent::__construct($em, $validator);
        $this->repository = $em->getRepository(Entreprises::class);
    }

    
    /**
     * Retrieves a collection of entreprises resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves a collection of Entreprises resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Entreprises::class))
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
     * @SWG\Tag(name="entreprises")
     * @Security(name="Bearer")
     *
     * @Rest\Get("/entreprises")
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
    public function listEntreprises(ParamFetcherInterface $paramFetcher)//: array
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
     * Retrieves an Entreprise resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves a Entreprise resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Entreprises::class))
     *     )
     * )
	 * @SWG\Parameter(
	 *     name="id",
	 * 	   in="path",
	 * 	   required=true,
	 * 	   type="integer"
	 * )
     * @SWG\Tag(name="entreprises")
     * @Security(name="Bearer")
     *
     * 
     * @Rest\Get(
     *     path="/entreprises/{id}",
     *     name="app_entreprise_show",
     *     requirements = {"id"="\d+"}
     * )
     * 
     * @Rest\View(populateDefaultVars=false)
     */
    public function getEntreprise(Entreprises $entreprise) : Entreprises
    {
        return $entreprise;
    }

    /**
     * Creates an Entreprises resource
     * 
     * @SWG\Response(
     *     response=200,
     *     description="The created Entreprise resource",
     *     @Model(type=Entreprises::class)
     * )
     * @SWG\Parameter(
     *     name="entreprise",
     *     in="body",
     *     @Model(type=Entreprises::class)
     * )
     * @SWG\Tag(name="entreprises")
     * @Security(name="Bearer")
     *
     * @Rest\Post(
     *         path = "/entreprises",
     *         name = "api_entreprise_create"
     * )
     * @Rest\View(populateDefaultVars=false, StatusCode = 201)
     * @ParamConverter("entreprise", class="App\Entity\Entreprises", converter="fos_rest.request_body")
     */
    public function createEntreprise(Entreprises $entreprise, ConstraintViolationList $violations): Entreprises
    {
        $this->dataValidator->validate($violations);

        $this->em->persist($entreprise);
        $this->em->flush();

        return $entreprise;
    }

    /**
     * Replaces Entreprise resource
     * @SWG\Response(
     *     response=200,
     *     description="The updated Entreprise resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Entreprises::class))
     *     )
     * )
	 * @SWG\Parameter(
	 *      name="id",
	 * 	    in="path",
	 * 	    required=true,
	 * 	    type="integer"
	 * 	)
     * @SWG\Parameter(
     *     name="newEntreprise",
     *     in="body",
     *     @Model(type=Entreprises::class)
     * )
     * @SWG\Tag(name="entreprises")
     * @Security(name="Bearer")
     *
     *   
     * @Rest\Put(
     *     path="/entreprises/{id}",
     *     name="app_entreprise_update"
     * )
     * @Rest\View(populateDefaultVars=false)
     * @ParamConverter("newEntreprise", class="App\Entity\Entreprises", converter="fos_rest.request_body")
     */
    public function putEntreprise(Entreprises $entreprise, Entreprises $newEntreprise, ConstraintViolationList $violations): Entreprises
    {
        $this->dataValidator->validate($violations);
        
        $entreprise = $this->repository->update($entreprise, $newEntreprise);
        
        $this->em->persist($entreprise);
        $this->em->flush();
        
        return $entreprise;
    }

    /**
     * Removes a Entreprises resource
     * @SWG\Response(
     *     response=202,
     *     description="Removes a entreprise resource"
     * )
	 * @SWG\Parameter(
	 *      name="id",
	 * 	    in="path",
	 * 	    required=true,
	 * 	    type="integer"
	 * 	)
     * @SWG\Tag(name="entreprises")
     * @Security(name="Bearer")
     * @Rest\Delete(
     *     path="/entreprises/{id}",
     *     name="app_entreprise_delete"
     * )
     * 
     * @Rest\View(
     *      populateDefaultVars=false,
     *      StatusCode = 202
     * )
     */
    public function deleteEntreprise(Entreprises $entreprise)
    {
        $this->em->remove($entreprise);
        $this->em->flush();
    }

    /*
     * Add a Prestations resource to a Entreprises resource
     * @SWG\Response(
     *     response=200,
     *     description="The added Prestations resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Prestations::class))
     *     )
     * )
	 * @SWG\Parameter(
	 *      name="id",
	 * 	    in="path",
	 * 	    required=true,
	 * 	    type="integer"
	 * 	)
     * @SWG\Parameter(
     *     name="newPrestation",
     *     in="body",
     *     description="Prestations Resource to add",
     *     @Model(type=Prestations::class)
     * )
     * @SWG\Tag(name="entreprises")
     * @Security(name="Bearer")
     *
     *   
     * @Rest\Put(
     *     path="/entreprises/{id}/prestation",
     *     name="app_entreprise_add_prestation"
     * )
     * @Rest\View(populateDefaultVars=false)
     * @ParamConverter("newPrestation", class="App\Entity\Prestations", converter="fos_rest.request_body")
     *
    public function addPrestation(Entreprises $entreprise, Prestations $newPrestation, ConstraintViolationList $violations)
    {
        $this->dataValidator->validate($violations);

        // $prestRepo = $this->getDoctrine()->getRepository(Prestations::class);
        // $prestation = $prestRepo->findOrCreate($newPrestation);
        
        $entreprise->addPrestation($newPrestation);

        $this->em->persist($entreprise);
        $this->em->flush();
        
        return $newPrestation;
    }
    */

}

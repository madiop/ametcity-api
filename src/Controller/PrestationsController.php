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

use App\Repository\PrestationsRepository;
use App\Entity\Prestations;
use App\Entity\Categories;
use App\Validators\Validator;

class PrestationsController extends GenericController
{
    private $repository;

    public function __construct(EntityManagerInterface $em, 
                                Validator $validator)
    {
        parent::__construct($em, $validator);
        $this->repository = $em->getRepository(Prestations::class);
    }

    
    /**
     * Retrieves a collection of prestations resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves a collection of prestation resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Prestations::class))
     *     )
     * )
     * @SWG\Parameter(
     *     name="type",
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
     * @SWG\Tag(name="prestations")
     * @Security(name="Bearer")
     *
     * @Rest\Get("/prestations")
     * @Rest\QueryParam(
     *     name="type",
     *     nullable=true,
     *     description="The type off prestations to search for. (produit or service)"
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
    public function listPrestations(ParamFetcherInterface $paramFetcher)//: array
    {
        $pager = $this->repository->search(
            $paramFetcher->get('type'),
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
     * Retrieves an Prestation resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves a Prestation resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Prestations::class))
     *     )
     * )
	 * @SWG\Parameter(
	 *     name="id",
	 * 	   in="path",
	 * 	   required=true,
	 * 	   type="integer"
	 * )
     * @SWG\Tag(name="prestations")
     * @Security(name="Bearer")
     *
     * 
     * @Rest\Get(
     *     path="/prestations/{id}",
     *     name="app_prestation_show",
     *     requirements = {"id"="\d+"}
     * )
     * 
     * @Rest\View(populateDefaultVars=false, serializerEnableMaxDepthChecks=true)
     */
    public function getPrestation(Prestations $prestation) : Prestations
    {
        return $prestation;
    }

    /**
     * Creates an Prestations resource
     * 
     * @SWG\Response(
     *     response=200,
     *     description="The created Prestation resource",
     *     @Model(type=Prestations::class)
     * )
     * @SWG\Parameter(
     *     name="prestation",
     *     in="body",
     *     @Model(type=Prestations::class)
     * )
     * @SWG\Tag(name="prestations")
     * @Security(name="Bearer")
     *
     * @Rest\Post(
     *         path = "/prestations",
     *         name = "api_prestation_create"
     * )
     * @Rest\View(populateDefaultVars=false, StatusCode = 201)
     * @ParamConverter("prestation", class="App\Entity\Prestations", converter="fos_rest.request_body")
     */
    public function createPrestation(Prestations $prestation, ConstraintViolationList $violations): Prestations
    {
        $this->dataValidator->validate($violations);

        if(is_null($prestation->getProfessionnel()) && is_null($prestation->getEntreprises())){
            throw $this->createNotFoundException('Le propriétaire de la prestation n\'est pas spécifié');
        }
        
        $idEnt = !is_null($prestation->getEntreprises()) ? $prestation->getEntreprises()->getId() : NULL;
        $idProf = !is_null($prestation->getProfessionnel()) ? $prestation->getProfessionnel()->getId() : NULL;

        $this->repository->sanitize($prestation);

        if(!is_null($idProf) && is_null($prestation->getProfessionnel())){
            throw $this->createNotFoundException('Le professionnel associé n\'existe pas');
        }

        if(!is_null($idEnt) && is_null($prestation->getEntreprises())){
            throw $this->createNotFoundException('L\'entreprise associé n\'existe pas');
        }
        
        $this->em->persist($prestation);
        $this->em->flush();

        return $prestation;
    }

    /**
     * Replaces Prestation resource
     * @SWG\Response(
     *     response=200,
     *     description="The updated Prestation resource",
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
     *     @Model(type=Prestations::class)
     * )
     * @SWG\Tag(name="prestations")
     * @Security(name="Bearer")
     *
     *   
     * @Rest\Put(
     *     path="/prestations/{id}",
     *     name="app_prestation_update"
     * )
     * @Rest\View(populateDefaultVars=false)
     * @ParamConverter("newPrestation", class="App\Entity\Prestations", converter="fos_rest.request_body")
     */
    public function putPrestation(Prestations $prestation, Prestations $newPrestation, ConstraintViolationList $violations): Prestations
    {
        $this->dataValidator->validate($violations);

        $prestation = $this->repository->update($prestation, $newPrestation);
        
        $this->em->persist($prestation);
        $this->em->flush();
        
        return $prestation;
    }

    /**
     * Removes a Prestations resource
     * @SWG\Response(
     *     response=202,
     *     description="Removes a prestation resource"
     * )
	 * @SWG\Parameter(
	 *      name="id",
	 * 	    in="path",
	 * 	    required=true,
	 * 	    type="integer"
	 * 	)
     * @SWG\Tag(name="prestations")
     * @Security(name="Bearer")
     * @Rest\Delete(
     *     path="/prestations/{id}",
     *     name="app_prestation_delete"
     * )
     * 
     * @Rest\View(
     *      populateDefaultVars=false,
     *      StatusCode = 202
     * )
     */
    public function deletePrestation(Prestations $prestation)
    {
        $this->em->remove($prestation);
        $this->em->flush();
    }

    private function verifiePrestation(Prestations $prestation){

    }
}

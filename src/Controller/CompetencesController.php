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

use App\Repository\CompetencesRepository;
use App\Entity\Competences;
use App\Validators\Validator;


class CompetencesController extends GenericController
{
    private $repository;

    public function __construct(CompetencesRepository $repository, 
                                EntityManagerInterface $em, 
                                Validator $validator)
    {
        parent::__construct($em, $validator);
        $this->repository = $repository;
    }

    /**
     * Retrieves a collection of Competences resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves a collection of Competences resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Competences::class))
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
     * @SWG\Tag(name="competences")
     * @Security(name="Bearer")
     *
     * @Rest\Get(
     *     path="/competences",
     *     name="app_competence_list"
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
    public function listCompetencess(ParamFetcherInterface $paramFetcher)//: array
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
     * Retrieves an Competences resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves an Competences resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Competences::class))
     *     )
     * )
	 * @SWG\Parameter(
	 *     name="id",
	 * 	   in="path",
	 * 	   required=true,
	 * 	   type="integer"
	 * )
     * @SWG\Tag(name="competences")
     * @Security(name="Bearer")
     * 
     * @Rest\Get(
     *     path="/competences/{id}",
     *     name="app_competence_show",
     *     requirements = {"id"="\d+"}
     * )
     * 
     * @Rest\View(populateDefaultVars=false)
     */
    public function getCompetences(Competences $competence) : Competences
    {
        return $competence;
    }

    /**
     * Update Competences resource
     * @SWG\Response(
     *     response=200,
     *     description="The updated Competences resource",
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
     *     name="newCompetences",
     *     in="body",
     *     @Model(type=Competences::class)
     * )
     * @SWG\Tag(name="competences")
     * @Security(name="Bearer")
     * @Rest\Put(
     *     path="/competences/{id}",
     *     name="app_competence_update"
     * )
     * @Rest\View(populateDefaultVars=false)
     * @ParamConverter("newCompetences", class="App\Entity\Competences", converter="fos_rest.request_body")
     */
    public function putCompetences(Competences $competence, Competences $newCompetences, ConstraintViolationList $violations): Competences
    {
        $this->dataValidator->validate($violations);

        $competence->setNom($newCompetences->getNom());

        $this->em->persist($competence);
        $this->em->flush();
        
        return $competence;
    }
}

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

use App\Repository\RolesRepository;
use App\Entity\Roles;
use App\Validators\Validator;
class RolesController extends GenericController
{   
    private $repository;

    public function __construct(EntityManagerInterface $em, 
                                Validator $validator)
    {
        parent::__construct($em, $validator);
        $this->repository = $em->getRepository(Roles::class);
    }

    /**
     * Retrieves a collection of Roles resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves a collection of Roles resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Roles::class))
     *     )
     * )
     * @SWG\Tag(name="roles")
     * @Security(name="Bearer")
     *
     * @Rest\Get("/roles")
     * @Rest\View(populateDefaultVars=false)
     */
    public function listRoles(): array
    {
        return $this->repository->findAll();
    }

    /**
     * Retrieves an Roles resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves an Roles resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Roles::class))
     *     )
     * )
     * @SWG\Tag(name="roles")
     * @Security(name="Bearer")
     * 
     * @Rest\Get(
     *     path="/roles/{id}",
     *     name="app_role_show",
     *     requirements = {"id"="\d+"}
     * )
     * 
     * @Rest\View(populateDefaultVars=false, serializerEnableMaxDepthChecks=true)
     */
    public function getRole(Roles $role) : Roles
    {
        return $role;
    }

    /**
     * Creates an Roles resource
     * @SWG\Response(
     *     response=200,
     *     description="Create an Roles resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Roles::class))
     *     )
     * )
     * @SWG\Parameter(
     *     name="role",
     *     in="body",
     *     @Model(type=Roles::class)
     * )
     * @SWG\Tag(name="roles")
     * @Security(name="Bearer")
     * @Rest\Post(
     *         path = "/roles",
     *         name = "api_role_create"
     * )
     * @Rest\View(populateDefaultVars=false, StatusCode = 201)
     * @ParamConverter("role", class="App\Entity\Roles", converter="fos_rest.request_body")
     */
    public function createRole(Roles $role, ConstraintViolationList $violations): Roles
    {
        $this->dataValidator->validate($violations);

        $this->em->persist($role);
        $this->em->flush();

        return $role;
    }

    /**
     * Replaces Roles resource
     * @SWG\Response(
     *     response=200,
     *     description="The updated Roles resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Roles::class))
     *     )
     * )
	 * @SWG\Parameter(
	 *      name="id",
	 * 	    in="path",
	 * 	    required=true,
	 * 	    type="integer"
	 * 	)
     * @SWG\Parameter(
     *     name="newRole",
     *     in="body",
     *     @Model(type=Roles::class)
     * )
     * @SWG\Tag(name="roles")
     * @Security(name="Bearer")
     * @Rest\Put(
     *     path="/roles/{id}",
     *     name="app_role_update"
     * )
     * @Rest\View(populateDefaultVars=false)
     * @ParamConverter("newRole", class="App\Entity\Roles", converter="fos_rest.request_body")
     */
    public function putRole(Roles $role, Roles $newRole, ConstraintViolationList $violations): Roles
    {
        $this->dataValidator->validate($violations);

        $role->setName($newRole->getName());
        $role->setDescription($newRole->getDescription());

        $this->em->persist($role);
        $this->em->flush();
        
        return $role;
    }

    /**
     * Removes an Roles resource
     * @SWG\Response(
     *     response=202,
     *     description="Removes the Roles resource"
     * )
	 * @SWG\Parameter(
	 *      name="id",
	 * 	    in="path",
	 * 	    required=true,
	 * 	    type="integer"
	 * 	)
     * @SWG\Tag(name="roles")
     * @Security(name="Bearer")
     * @Rest\Delete(
     *     path="/roles/{id}",
     *     name="app_role_delete"
     * )
     * 
     * @Rest\View(
     *      populateDefaultVars=false,
     *      StatusCode = 202
     * )
     */
    public function deleteRole(Roles $role)
    {
        $this->em->remove($role);
        $this->em->flush();
    }
}

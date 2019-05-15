<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

use App\Entity\User;
use App\Entity\Roles;
use App\Entity\Prestations;
use App\Entity\Specialites;
use App\Validators\Validator;

class UserController extends GenericController
{
    private $repository;

    public function __construct(EntityManagerInterface $em, 
                                Validator $validator)
    {
        parent::__construct($em, $validator);
        $this->repository = $em->getRepository(User::class);
    }

    
    /**
     * Retrieves a collection of User resource
     * @SWG\Response(
     *     response=200,
     *     description="Retrieves a collection of User resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=User::class))
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
     * @SWG\Tag(name="users")
     * @Security(name="Bearer")
     *
     * @Rest\Get(
     *     path="/users",
     *     name="app_user_list"
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
    public function listUser(ParamFetcherInterface $paramFetcher)//: array
    {
        $pager = $this->repository->loadUserByUsername(
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
    * Retrieves an User resource
    * @SWG\Response(
    *     response=200,
    *     description="Retrieves a User resource",
    *     @SWG\Schema(
    *         type="array",
    *         @SWG\Items(ref=@Model(type=User::class))
    *     )
    * )
    * @SWG\Parameter(
    *     name="id",
    * 	   in="path",
    * 	   required=true,
    * 	   type="integer"
    * )
    * @SWG\Tag(name="users")
    * @Security(name="Bearer")
    *
    * 
    * @Rest\Get(
    *     path="/users/{id}",
    *     name="app_user_show",
    *     requirements = {"id"="\d+"}
    * )
    * 
    * @Rest\View(populateDefaultVars=false, serializerEnableMaxDepthChecks=true)
    */
    public function getOneUser(User $user) : User
    {
       return $user;
    }

   /**
    * Creates an User resource
    * 
    * @SWG\Response(
    *     response=200,
    *     description="The created User resource",
    *     @Model(type=User::class)
    * )
    * @SWG\Parameter(
    *     name="user",
    *     in="body",
    *     @Model(type=User::class)
    * )
    * @SWG\Tag(name="users")
    * @Security(name="Bearer")
    *
    * @Rest\Post(
    *         path = "/users",
    *         name = "api_user_create"
    * )
    * @ParamConverter("user", class="App\Entity\User", converter="fos_rest.request_body")
    * @Rest\View(populateDefaultVars=false, StatusCode = 201, serializerEnableMaxDepthChecks=true)
    */
    public function registerUser(User $user, ConstraintViolationList $violations, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer): User
    {
        $this->dataValidator->validate($violations);
        
        $password = $user->getPassword();
        $user->setPassword('');
        $user->setPassword($encoder->encodePassword($user, $password));
        $user->setDateInscription(new \DateTime());
        $user->setIsActive(false);
        // $random = sha1(random_bytes(12));
        $user->setConfirmationToken(sha1(random_bytes(12)));

        // var_dump($random);
        // exit;

        $roles = $user->getRoles();
        $user->setRoles(null);

        $roleRepo = $this->getDoctrine()->getRepository(Roles::class);
        $userRole = $roleRepo->findOneByName('ROLE_USER');
        $user->addRole($userRole);
        
        foreach($roles as $roleName){
            if(!is_null($role = $roleRepo->findOneByName($roleName))){
            
                $user->addRole($role);
                
                if($role->getName() == 'ROLE_PROFESSIONNEL' && is_null($user->getProfessionnel())){
                    throw $this->createNotFoundException('Les informations du professionnel ne sont pas renseignées!');
                }
                if($role->getName() == 'ROLE_ENTREPRISE' && is_null($user->getEntreprise())){
                    throw $this->createNotFoundException('Les informations de l\'entreprise ne sont pas renseigné!');
                }
            }
        }

        $message = (new \Swift_Message('Ametcity activation compte'))
                        ->setFrom('noreplay.ametcity@ametcity.com')
                        ->setTo('madiop44@gmail.com')
                        ->setBody(
                            $this->renderView(
                                'emails/registration.html.twig',
                                [
                                    'name' => $user->getPrenom() . ' ' . $user->getNom(),
                                    'key' => $user->getConfirmationToken()
                                 ]
                            ),
                            'text/html'
                        );
        $mailer->send($message);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }

    /**
     * Activate user account
     * @SWG\Response(
     *     response=200,
     *     description="The activated user infoormations",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=User::class))
     *     )
     * )
	 * @SWG\Parameter(
	 *      name="key",
	 * 	    in="path",
	 * 	    required=true,
	 * 	    type="string"
	 * 	)
     * @SWG\Tag(name="users")
     * @Security(name="Bearer")
     * 
     * @Rest\QueryParam(
     *     name="key",
     *     nullable=false,
     *     description="Token de confirmation du compte"
     * )
     * @Rest\Put(
     *     path="/users",
     *     name="app_user_activate"
     * )
     * @Rest\View(populateDefaultVars=false, serializerEnableMaxDepthChecks=true)
     */
    public function activateUser(ParamFetcherInterface $paramFetcher): User
    {
        $key = $paramFetcher->get('key');
        $user = $this->repository->findOneByToken($key);

        if(is_null($user)){
            throw $this->createNotFoundException('La clé d\'activation est incorrect ou a expirée !');
        }
        $user->setIsActive(true);
        $user->setConfirmationToken(null);

        $this->em->persist($user);
        $this->em->flush();
        
        return $user;
    }

    /**
     * Replaces User resource
     * @SWG\Response(
     *     response=200,
     *     description="The updated User resource",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=User::class))
     *     )
     * )
	 * @SWG\Parameter(
	 *      name="id",
	 * 	    in="path",
	 * 	    required=true,
	 * 	    type="integer"
	 * 	)
     * @SWG\Parameter(
     *     name="newUser",
     *     in="body",
     *     @Model(type=User::class)
     * )
     * @SWG\Tag(name="users")
     * @Security(name="Bearer")
     *
     *   
     * @Rest\Put(
     *     path="/users/{id}",
     *     name="app_user_update"
     * )
     * @Rest\View(populateDefaultVars=false)
     * @ParamConverter("newUser", class="App\Entity\User", converter="fos_rest.request_body")
     */
    public function putUser(User $user, User $newUser, ConstraintViolationList $violations): User
    {
        $this->dataValidator->validate($violations);
        
        $user = $this->repository->update($user, $newUser);
        
        $this->em->persist($user);
        $this->em->flush();
        
        return $user;
    }

}

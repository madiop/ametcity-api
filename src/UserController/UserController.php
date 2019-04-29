<?php

namespace App\UserController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\UserRepository;
use App\Entity\User;
use App\Validators\Validator;

class UserController extends AbstractController
{
    private $repository;
    private $em;
    private $dataValidator;

    public function __construct(UserRepository $repository, EntityManagerInterface $em, Validator $validator)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->dataValidator = $validator;
    }

    /**
     * User registration
     * 
     * @Rest\Post(
     *         path = "/register",
     *         name = "app_user_registration"
     * )
     * @Rest\View(populateDefaultVars=false, StatusCode = 201)
     * @ParamConverter("user", class="App\Entity\User", converter="fos_rest.request_body")
     */
    //  * @Rest\View(populateDefaultVars=false, StatusCode = 201)
    public function register(User $user, UserPasswordEncoderInterface $encoder)
    {
        // echo $user->getPassword();
        // var_dump($user);
        // exit;
        $user->setPassword($encoder->encodePassword($user, 'thiam'));
        // $user->setDateInscription(new \DateTime());
        $user->setIsActive(true);

        // var_dump($user);
        // exit;

        $this->em->persist($user);
        $this->em->flush();
        return new Response(sprintf('User %s successfully created', $user->getUsername()));
    }

    /**
     * @Route("/index", name="user")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}

<?php
namespace App\UserController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use FOS\RestBundle\Controller\Annotations as Rest;

use App\Entity\User;

class AuthController extends AbstractController
{
    

    /**
     * User registration
     * @SWG\Response(
     *     response=200,
     *     description="User registration"
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
     *         path = "/register",
     *         name = "app_user_registration"
     * )
     * @Rest\View(populateDefaultVars=false, StatusCode = 201)
     * @ParamConverter("user", class="App\Entity\User", converter="fos_rest.request_body")
     */
    //  * @Rest\View(populateDefaultVars=false, StatusCode = 201)
    public function register(User $user, UserPasswordEncoderInterface $encoder)
    {
        // var_dump($user);
        // exit;
        $password = $user->getPassword();
        $user->setPassword('');
        $user->setPassword($encoder->encodePassword($user, $password));
        $user->setDateInscription(new \DateTime());
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return new Response(sprintf('User %s successfully created', $user->getUsername()));
    }
}

<?php
namespace App\UserController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;

class AuthController extends AbstractController
{
    

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
        var_dump($user);
        exit;
        $password = $user->getPassword();
        $user->setPassword('');
        $user->setPassword($encoder->encodePassword($user, $password));
        // $user->setDateInscription(new \DateTime());
        // $user->setIsActive(true);

        // var_dump($user);
        // exit;
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return new Response(sprintf('User %s successfully created', $user->getUsername()));
    }

    public function api()
    {
        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
    }
}

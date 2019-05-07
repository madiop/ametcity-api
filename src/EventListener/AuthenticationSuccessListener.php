<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Entity\User;

    // public function serialize($data, $format, array $context = Array()){}
    // public function deserialize($data, $type, $format, array $context = Array()){}
class AuthenticationSuccessListener //implements SerializerInterface
{
    /*
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        /**
         * @TODO
         * désinstaller le bundle guzzle
         */
        $data = $event->getData();
        $user = $event->getUser();

        // $encoders = new JsonEncoder();
        // $normalizers = new ObjectNormalizer();
        
        // $serializer = new Serializer($normalizers, $encoders);
        
        // $data['user'] = $serializer->serialize($user, 'json');
        $data['user'] = [
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'telephone' => $user->getTelephone(),
            'status' => $user->getStatus(),
            'adresse' => $user->getAdresse(),
            'civilite' => $user->getCivilite(),
            'ville' => $user->getVille(),
            'pays' => $user->getPays(),
            // 'roles' => $user->getRoles()
        ];

        $event->setData($data);
    }
}
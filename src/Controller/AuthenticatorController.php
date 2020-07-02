<?php

namespace App\Controller;

use App\Entity\Pro;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AuthenticatorController extends AbstractController
{
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();

        $email = json_decode($request->getContent())->email;
        $password = json_decode($request->getContent())->password;
        $nom = json_decode($request->getContent())->nom;
        $prenom = json_decode($request->getContent())->prenom;
        $numSiret = json_decode($request->getContent())->numSiret;
        $numTelephone = json_decode($request->getContent())->numTelephone;
        $user = new Pro();
        $user->setEmail($email);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setNumSiret($numSiret);
        $user->setNumTelephone($numTelephone);
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($encoder->encodePassword($user, $password));
        $em->persist($user);
        $em->flush();

        return new Response(sprintf('User %s successfully created', $user->getUsername()));
    }

    public function api()
    {
        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
    }

    public function getCompleteUser() {
        return $this->json($this->getUser());
    }
}
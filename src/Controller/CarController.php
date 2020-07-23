<?php

namespace App\Controller;

use App\Entity\Garages;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Car;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class CarController extends AbstractController
{
    public function addCar(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

            $em = $serializer->deserialize($request->getContent(), Car::class, 'json');
            $idGarage = json_decode($request->getContent())->garageId;

            $em->setGarages($idGarage);
            $errors = $validator->validate($em);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }
            $entityManager->persist($em);
            $entityManager->flush();
            $data = [
                'status' => 201,
                'message' => 'L\'utilisateur a bien été ajouté'
            ];
            return new JsonResponse($data, 201);
        }
}

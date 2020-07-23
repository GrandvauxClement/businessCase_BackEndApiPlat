<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Serializer\SerializerInterface;

class UploadController extends AbstractController
{
    private $uploadImageVoitureDirectory;
    /**
     * @Route(
     *     name="upload_voiture",
     *     path="api/voitures/upload",
     *     methods={"POST"}
     * )
     */
    public function uploadExcel(Request $request, ParameterBagInterface $parameterBag, SerializerInterface $serializer)
    {
        $userConnect = $this->getUser()->getUsername();
        $this->uploadImageVoitureDirectory = $parameterBag->get('upload_voiture_directory');
        $file = $request->files->get('images');
        $errors = [];
        $result = [];
        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $newFilename = uniqid() . '.' . $file->guessExtension();

            if (!in_array($file->guessExtension(), ['jpeg', 'jpg', 'png']) ){
                $errors = ['success'=> false, 'message'=> 'Nous acceptons que les fichiers excel xls'];
            } else {
                try {
                    $file->move(
                        $this->uploadImageVoitureDirectory,
                        $newFilename
                    );

                } catch (FileException $e) {
                    $errors = ['success'=> false, 'message'=> $e];
                }


                $errors = ['success'=> true, 'file'=> $newFilename];
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 200, [
                    'Content-Type' => 'application/json'
                ]);

            }
        } /*else {
            $errors = ['success'=> false, 'message'=> 'Fichier introuvable'];

        }*/


        $errors = $serializer->serialize($errors, 'json');
        return new Response($errors, 500, [
            'Content-Type' => 'application/json'
        ]);

    }

}

<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Utils;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use function Symfony\Component\String\u;

/**
 * This class is used to provide an example of integrating simple classes as
 * services into a Symfony application.
 *
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class Validator
{
    public function validateUsername(?string $username): string
    {
        if (empty($username)) {
            throw new InvalidArgumentException('L\'username doit etre renseigné !.');
        }

        if (1 !== preg_match('/^[a-z0-9_]+$/', $username)) {
            throw new InvalidArgumentException('L\'username ne peut contenir que des minuscules, des chiffres et l\'underscore (_)');
        }

        return $username;
    }

    public function validatePassword(?string $plainPassword): string
    {
        if (empty($plainPassword)) {
            throw new InvalidArgumentException('Le mot de passe doit être renseigné');
        }

        if (u($plainPassword)->trim()->length() < 6) {
            throw new InvalidArgumentException('Le mot de passe doit contenir 6 caractère au minimum');
        }

        if ( 1 !== preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/', $plainPassword)){

            throw new InvalidArgumentException('Le mot de passe doit contenir au moins 1 majuscule, 1 minuscule et 1 chiffre ');

        }

        return $plainPassword;
    }

    public function validateEmail(?string $email): string
    {
        if (empty($email)) {
            throw new InvalidArgumentException('L\'email doit être renseigné');
        }

        /*if (null === u($email)->indexOf('@')) {

            throw new InvalidArgumentException('The email should look like a real email.');
        }*/

        if( false == filter_var($email, FILTER_VALIDATE_EMAIL) ) {
            throw new InvalidArgumentException('Email incorrect, Veuillez saisir un format d\'email valide : exemple@expemple.com.');
        }

        return $email;
    }

    public function validatePrenom(?string $prenom): string
    {
        if (empty($prenom)) {
            throw new InvalidArgumentException('Le prenom doit être renseigné.');
        }

        if (1 !== preg_match('/^[a-z-àâçèéêîôùû]+$/', $prenom)) {
            throw new InvalidArgumentException('Le prénom ne peut contenir que des minuscules');
        }

        return $prenom;
    }

    public function validateNom(?string $nom): string
    {
        if (empty($nom)) {
            throw new InvalidArgumentException('Le nom de famille doit être rensiegné.');
        }

        if (1 !== preg_match('/^[a-z-àâçèéêîôùû]+$/', $nom)) {
            throw new InvalidArgumentException('Le Nom de famille ne peut contenir que des minuscules');
        }

        return $nom;
    }

    public function validateNumTelephone(?string $numTelephone): string
    {
        if (empty($numTelephone)) {
            throw new InvalidArgumentException('Le numéro de téléphone doit être rensiegné.');
        }

        $phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        try {
            $phoneNumberObject = $phoneNumberUtil->parse($numTelephone, 'FR');

            if ($phoneNumberUtil->isValidNumber($phoneNumberObject) === false ){
                throw new InvalidArgumentException('Veuillez saisir un numéro de téléphone français valide.');
            }
        }
        catch(\Exception $e){
            throw new InvalidArgumentException('Veuillez saisir un numéro de téléphone français valide.');
        }

        return $numTelephone;
    }
}

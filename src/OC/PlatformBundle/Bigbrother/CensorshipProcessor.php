<?php
// src/OC/PlatformBundle/Bigbrother/CensorshipProcessor.php

namespace OC\PlatformBundle\Bigbrother;

use Symfony\Component\Security\Core\User\UserInterface;

class CensorshipProcessor
{
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    // Méthode pour notifier par e-mail un administrateur
    public function notifyEmail($message, UserInterface $user)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject("Nouveau message d'un utilisateur surveillé")
            ->setFrom('blackholus@hotmail.com')
            ->setTo('blackholus@hotmail.com')
            ->setBody("L'utilisateur surveillé '".$user->getUsername()."' a posté le message suivant : '".$message."'");

        $this->mailer->send($message);
    }

    // Méthode pour censurer un message (supprimer les mots interdits)
    public function censorMessage($message)
    {
        $message = str_replace(array('top secret', 'mot interdit'), '', $message);

        return $message;
    }
}
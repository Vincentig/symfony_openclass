<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 09/02/2016
 * Time: 14:36
 */

namespace OC\PlatformBundle\Beta;


use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class BetaListener
{
// Notre processeur
    protected $betaHtml;

    // La date de fin de la version bêta :
    // - Avant cette date, on affichera un compte à rebours (J-3 par exemple)
    // - Après cette date, on n'affichera plus le « bêta »
    protected $endDate;

    public function __construct(BetaHTML $betaHtml, $endDate)
    {
        $this->betaHtml = $betaHtml;
        $this->endDate  = new \Datetime($endDate);
    }

    public function processBeta(FilterResponseEvent $event)
    {

        // On teste si la requête est bien la requête principale (et non une sous-requête)
        if (!$event->isMasterRequest()) {
            return;
        }





        $date = new \DateTime();
        $remainingDays = $date->diff($this->endDate)->format('%r%d');



        // Si la date est dépassée, on ne fait rien
        if ($remainingDays <= 0) {
            return;
        }

        // On utilise notre BetaHTML
        $response = $this->betaHtml->displayBeta($event->getResponse(), $remainingDays);
        // On met à jour la réponse avec la nouvelle valeur
        $event->setResponse($response);


    }
}
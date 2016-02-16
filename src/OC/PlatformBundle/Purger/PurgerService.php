<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 04/02/2016
 * Time: 13:07
 */

namespace OC\PlatformBundle\Purger;


class PurgerService
{
    protected $em;
    protected $session;

    /**
     * PurgerService constructor.
     * @param $doctrine
     */
    public function __construct($em , $session)
    {
        $this->em = $em;
        $this->session = $session;

    }


    public function purge($days)
    {

        $date = new \Datetime($days.' days ago');

        $articleRepository = $this->em->getRepository('OCPlatformBundle:Advert');

        $articles = $articleRepository->getAdvertsModificationDateOlderThanDate($date);

        $nbSupprime = 0;
        foreach ($articles as $index => $article) {
            $this->em->remove($article);
            $nbSupprime++;
        }

        $this->em->flush();

        $texteFlashbag = 'Aucune annonce supprimée';
        if($nbSupprime == 1)
        {
            $texteFlashbag = $nbSupprime.' annonce supprimée';
        }
        elseif( $nbSupprime > 1)
        {
            $texteFlashbag = $nbSupprime.' annonces supprimées';
        }

        $this->session->getFlashBag()->add('info', $texteFlashbag);
    }


}
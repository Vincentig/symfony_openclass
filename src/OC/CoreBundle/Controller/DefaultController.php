<?php

namespace OC\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    public function indexAction()
    {

        $listAdverts = array(
            array(
                'title'   => 'Recherche développpeur Symfony2',
                'id'      => 1,
                'author'  => 'Alexandre',
                'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
                'date'    => new \Datetime('20.10.2015')),
            array(
                'title'   => 'Mission de webmaster',
                'id'      => 2,
                'author'  => 'Hugo',
                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
                'date'    => new \Datetime('25.10.2015')),
            array(
                'title'   => 'Offre de stage webdesigner',
                'id'      => 3,
                'author'  => 'Mathieu',
                'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
                'date'    => new \Datetime('29.10.2015')),
            array(
                'title'   => 'Offre d\'emploi',
                'id'      => 4,
                'author'  => 'Vincent',
                'content' => 'Nous proposons un poste super bien payé. Blabla…',
                'date'    => new \Datetime())
        );
        return $this->render('OCCoreBundle::index.html.twig', Array(
            'listAdverts' => $listAdverts
        ));
    }

    public function ContactAction(Request $request)
    {
        //gestion du message d'information.
        $session = $request->getSession();
        $session->getFlashBag()->add('info', 'La page de contact n\'est pas encore disponible. Merci de revenir plus tard' );

        //On redirige vers la page d'accueil
        return $this->redirect($this->generateUrl('oc_core_homepage'));
        return $this->render('OCCoreBundle::contact.html.twig');
    }
}

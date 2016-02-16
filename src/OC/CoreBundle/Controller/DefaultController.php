<?php

namespace OC\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    public function indexAction()
    {

        // On retourne simplement la vue de la page d'accueil
        // L'affichage des 3 dernières annonces utilisera le contrôleur déjà existant dans PlatformBundle
        return $this->get('templating')->renderResponse('OCCoreBundle::index.html.twig');

        // La méthode raccourcie $this->render('...') est parfaitement valable
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

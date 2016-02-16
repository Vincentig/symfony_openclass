<?php

namespace OC\PlatformBundle\Controller;


use OC\PlatformBundle\Bigbrother\BigbrotherEvents;
use OC\PlatformBundle\Bigbrother\MessagePostEvent;
use OC\PlatformBundle\Form\AdvertType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\AdvertSkill;

class AdvertController extends Controller
{
    public function indexAction($page)
    {
        if ($page < 1) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        // Ici je fixe le nombre d'annonces par page à 3
        // Mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
        $nbPerPage = 3;

        $em= $this->getDoctrine()->getManager();

        // On récupère notre objet Paginator
        $listAdverts =  $em
            ->getRepository('OCPlatformBundle:Advert')
            ->getAdverts($page, $nbPerPage);

        //var_dump($em->getRepository('OCPlatformBundle:Advert')->getAdvertsModificationDateOlderThanXDay(2));

        // On calcule le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
        $nbPages = ceil(count($listAdverts) / $nbPerPage);
        if ($nbPages == 0)
        {
            $nbPages = 1;
        }

        // Si la page n'existe pas, on retourne une 404
        if ($page !=1 && $page > $nbPages) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        // On donne toutes les informations nécessaires à la vue
        return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts,
            'nbPages' => $nbPages,
            'page' => $page
        ));
    }

    public function menuAction($limit)
    {


        $em = $this->getDoctrine()->getManager();
        $advertRepository = $em->getRepository('OCPlatformBundle:Advert');

//        $listAdverts = $advertRepository->findAll();
        $listAdverts = $advertRepository->findBy(array(), array('date' => 'desc'), $limit, 0);



        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
            // Tout l'intérêt est ici : le contrôleur passe
            // les variables nécessaires au template !
            'listAdverts' => $listAdverts
        ));
    }

  //  public function viewAction(Advert $advert)
    public function viewAction(Advert $advert)
    {
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
      //  $advert = $em
      //      ->getRepository('OCPlatformBundle:Advert')
      //      ->find($id)
      //  ;

     //   if (null === $advert) {
      //      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
     //   }

        // On récupère la liste des candidatures de cette annonce
        $listApplications = $em
            ->getRepository('OCPlatformBundle:Application')
            ->findBy(array('advert' => $advert))
        ;

         // On récupère maintenant la liste des AdvertSkill
        $listAdvertSkills = $em
            ->getRepository('OCPlatformBundle:AdvertSkill')
            ->findBy(array('advert' => $advert))
        ;

        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'advert'           => $advert,
            'listApplications' => $listApplications,
            'listAdvertSkills' => $listAdvertSkills
        ));
    }

    public function addAction(Request $request)
    {



/*
        // Création de l'entité Advert
        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony2.');
        $advert->setAuthor('Alexandre');
        $advert->setContent("Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…");

        // Création d'une première candidature
        $application1 = new Application();
        $application1->setAuthor('Marine');
        $application1->setContent("J'ai toutes les qualités requises.");

        // Création d'une deuxième candidature par exemple
        $application2 = new Application();
        $application2->setAuthor('Pierre');
        $application2->setContent("Je suis très motivé.");

        // Création de l'entité Image
        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('Job de rêve');

        // On lie les candidatures à l'annonce
        //$application1->setAdvert($advert);
        // $application2->setAdvert($advert);
        $advert->addApplication($application1);
        $advert->addApplication($application2);

        // On lie l'image à l'annonce
        $advert->setImage($image);

        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();


        // On récupère toutes les compétences possibles
        $listSkills = $em->getRepository('OCPlatformBundle:Skill')->findAll();

        // Pour chaque compétence
        foreach ($listSkills as $skill) {
            // On crée une nouvelle « relation entre 1 annonce et 1 compétence »
            $advertSkill = new AdvertSkill();

            // On la lie à l'annonce, qui est ici toujours la même
            $advertSkill->setAdvert($advert);
            // On la lie à la compétence, qui change ici dans la boucle foreach
            $advertSkill->setSkill($skill);

            // Arbitrairement, on dit que chaque compétence est requise au niveau 'Expert'
            $advertSkill->setLevel('Expert');

            // Et bien sûr, on persiste cette entité de relation, propriétaire des deux autres relations
            $em->persist($advertSkill);
        }


        // Étape 1 : On « persiste » l'entité
        $em->persist($advert);

        // Étape 1 bis : pour cette relation pas de cascade lorsqu'on persiste Advert, car la relation est
        // définie dans l'entité Application et non Advert. On doit donc tout persister à la main ici.
        $em->persist($application1);
        $em->persist($application2);

        // Étape 2 : On « flush » tout ce qui a été persisté avant
        $em->flush();




        // Reste de la méthode qu'on avait déjà écrit
        // if ($request->isMethod('POST')) {
        //     $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
        return $this->redirect($this->generateUrl('oc_platform_view', array('id' => $advert->getId())));
        //  }

        return $this->render('OCPlatformBundle:Advert:add.html.twig');
*/

        $advert = new Advert();

        $form = $this->createForm(new AdvertType(), $advert);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $advert->setUser($this->getUser());

            $event = new MessagePostEvent($advert->getContent(), $advert->getUser());
            // On déclenche l'évènement
            $this
                ->get('event_dispatcher')
                ->dispatch(BigbrotherEvents::onMessagePost, $event)
            ;

            // On récupère ce qui a été modifié par le ou les listeners, ici le message
            $advert->setContent($event->getMessage());

            $em=$this->getDoctrine()->getManager();
            $em->persist($advert);


            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');

            $uploadableManager->markEntityToUpload($advert->getImage(), $advert->getImage()->getUrl());


            $em->flush();

            return $this->redirectToRoute('oc_platform_view',array(
                'id'=>$advert->getId()
            ));
        }

        return $this->render('@OCPlatform/Advert/add.html.twig',array(
            'form'=>$form->createView()
        ));

    }


    public function editAction($id, Request $request)
    {
        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();

        // On récupère l'entité correspondant à l'id $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        // Si l'annonce n'existe pas, on affiche une erreur 404
        if ($advert == null) {
            throw $this->createNotFoundException("L'annonce d'id " . $id . " n'existe pas.");
        }


        $form = $this->createForm(new AdvertType(), $advert);
        $form ->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid())
        {
            $uploadableManager = $this->get('stof_doctrine_extensions.uploadable.manager');
            $uploadableManager->markEntityToUpload($advert->getImage(), $advert->getImage()->getUrl());

            $em->flush();

            return $this->redirectToRoute('oc_platform_view',array(
                'id'=>$advert->getId()
            ));
        }




        return $this->render('@OCPlatform/Advert/add.html.twig',array(
            'form'=>$form->createView()
        ));


    }

    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // On récupère l'annonce $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this->createFormBuilder()->getForm();

        if ($form->handleRequest($request)->isValid()) {
            $em->remove($advert);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

            return $this->redirect($this->generateUrl('oc_platform_home'));
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('OCPlatformBundle:Advert:delete.html.twig', array(
            'advert' => $advert,
            'form'   => $form->createView()
        ));
    }


    public function purgeAction($days, Request $request)
    {

        $servicePurge = $this->getPurge();
        $servicePurge->purge($days);

        return $this->redirect($this->generateUrl('oc_core_homepage'));

        //var_dump($articles);

        //return new Response('toto');
    }

    /**
     * @return PurgerService
     */
    protected function getPurge()
    {
        return $this->get('oc_platform.purge');
    }

    public function translationAction($name)
    {
        return $this->render('OCPlatformBundle:Advert:translation.html.twig', array(
            'name' => $name
        ));
    }

    /**
     * @ParamConverter("json")
     */
    public function ParamConverterAction($json)
    {
        return new Response(print_r($json, true));
    }

}
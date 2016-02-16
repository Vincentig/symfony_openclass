<?php

namespace OC\PlatformBundle\Controller;


use OC\PlatformBundle\Entity\Category;
use OC\PlatformBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\AdvertSkill;

class CategoryController extends Controller
{

    public function addAction(Request $request)
    {
        $category = new Category();

        $form = $this->createForm(new CategoryType(), $category);
        $form->handleRequest($request);


        if( $form->isSubmitted() &&  $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('oc_platform_home');
        }

        return $this->render('@OCPlatform/Category/add.html.twig', array(
            'form'=>$form->createView()
        ));


    }



}
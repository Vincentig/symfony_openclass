<?php
// src/OC/PlatformBundle/DataFixtures/ORM/LoadAdvert.php

namespace OC\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Application;

class LoadAdvert implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
	$advert1 = new Advert();
	$advert1->setImage();
	$advert1->setDate(new \Datetime('2016-01-03 10:02:25'));
	$advert1->setTitle('Développeur Symfony');
	$advert1->setAuthor('Phar');
	$advert1->setContent('On recherche un nouveau développeur Symfony 2.');
	$advert1->setPublished(false);
	$app1 = new Application();
	$app1->setAdvert($advert1);
	$app1->setAuthor('Moa');
	$app1->setContent('Je suis intéressé. J\'ai suivi le cours Symfony sur OCR');
	$app1->setDate(new \Datetime('2016-01-03 10:04:12'));
	$app2 = new Application();
	$app2->setAdvert($advert1);
	$app2->setAuthor('SuperMoa');
	$app2->setContent('Je suis trop fort en Symfony2. Virez tous les autres');
	$app2->setDate(new \Datetime('2016-01-04 09:24:54'));

	$advert2 = new Advert();
	$advert2->setImage();
	$advert2->setDate(new \Datetime('2016-01-04 19:42:45'));
	$advert2->setTitle('Développeur C++');
	$advert2->setAuthor('Phar');
	$advert2->setContent('On recherche un nouveau développeur C++.');
	$advert2->setPublished(false);

	$advert3 = new Advert();
	$advert3->setImage();
	$advert3->setDate(new \Datetime('2016-01-07 09:24:31'));
	$advert3->setTitle('Développeur Java');
	$advert3->setAuthor('Phar');
	$advert3->setContent('On recherche un nouveau développeur Java.');
	$advert3->setPublished(false);

	$advert4 = new Advert();
	$advert4->setImage();
	$advert4->setDate(new \Datetime('2016-01-13 11:52:25'));
	$advert4->setTitle('Développeur iOS');
	$advert4->setAuthor('Phar');
	$advert4->setContent('On recherche un nouveau développeur iOS.');
	$advert4->setPublished(false);

	$advert5 = new Advert();
	$advert5->setImage();
	$advert5->setDate(new \Datetime());
	$advert5->setTitle('Développeur Androïd');
	$advert5->setAuthor('Phar');
	$advert5->setContent('On recherche un nouveau développeur Androïd.');
	$advert5->setPublished(false);

	  $advert6 = new Advert();
	  $advert6->setImage();
	  $advert6->setDate(new \Datetime('2016-01-04 19:42:45'));
	  $advert6->setUpdatedAt(new \Datetime('2016-01-28 19:42:45'));
	  $advert6->setTitle('Développeur ploum ploum');
	  $advert6->setAuthor('Phar');
	  $advert6->setContent('On recherche un nouveau développeur C++.');
	  $advert6->setPublished(false);
	
    $manager->persist($advert1);
    $manager->persist($advert2);
    $manager->persist($advert3);
    $manager->persist($advert4);
    $manager->persist($advert5);
    $manager->persist($advert6);
	$manager->persist($app1);
	$manager->persist($app2);

    $manager->flush();
  }
}
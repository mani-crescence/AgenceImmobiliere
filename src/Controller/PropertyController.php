<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class PropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository; 

    public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
    {
      $this->repository = $repository;
      $this->em = $em;
    }



    /**
     * @Route("/biens/" , name="property.index")
     */
    public function index()
      {
         $property = $this->repository->findAllVisible();
         
         $this->em->flush();

             return $this->render("pages/property/index.html.twig" , [
               'current_menu'=>'properties'
        ]);
      }

      
      /**
       * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
       */
    public function show(Property $property, string $slug):Response
    {
          if ($property->getSlug() !== $slug )
          {
            return $this->redirectToRoute('property.show' , [
              'id' => $property->getId(),
              'slug' => $property->getSlug()
            ], 301);
          }
          return $this->render("pages/property/show.html.twig" , [
              'property' => $property,
              'current_menu'=>'properties'
        ]);
     }

}
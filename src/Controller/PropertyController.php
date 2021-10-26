<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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
    public function index(PaginatorInterface $paginator , Request $request)
      {
        //creer une entite qui va representer notre recherche
        //creer un formulaire
        //gerer le traitement dans le controlleur
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);

         $properties =$paginator->paginate(
           $this->repository->findAllVisibleQuery($search),
           $request->query->getInt('page', 1) ,
           12
         );  

          return $this->render("pages/property/index.html.twig" , [
               'current_menu'=>'properties',
               'properties' => $properties,
               'form' => $form->createView()
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
<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController
{

    /**
     * @var PropertyRepository
     */
    private $repository;

    public function __construct(PropertyRepository $repository , EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /** 
     *@Route("/admin", name="admin.property.index")
     */
    public function index()
    {
       $properties = $this->repository->findAll();
    
       return $this->render("pages/admin/property/index.html.twig", compact('properties'));

    }

    /**
     * @Route("/admin/property/create", name="admin.property.new")
     */
    public function new(Request $request)
    {
      $property = new Property(); 
      $form = $this->createForm(PropertyType ::class, $property);
      $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
           $this->em->persist($property);
           $this->em->flush();
           $this->addFlash('success' ,'bien créé avec succèss');
           return $this->redirectToRoute('admin.property.index');
        }
          return $this->render("pages/admin/property/new.html.twig",[
            'property'=>$property,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/admin/property/{id}", name="admin.property.edit" , methods="GET|POST")
     */
    public function edit(Property $property , Request $request)
    {
        $form = $this->createForm(PropertyType ::class,$property);
        $form->handleRequest($request);
         
        if($form->isSubmitted() && $form->isValid())
        {
           $this->em->flush();
           $this->addFlash('success' , 'bien modifié avec succèss');
           return $this->redirectToRoute('admin.property.index');
        }
        return $this->render("pages/admin/property/edit.html.twig",[
            'property'=>$property,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * Undocumented function
     * @method("POST")
     *@Route("/admin/property/delete/{id}", name="admin.property.delete" , methods={"POST"})
     * @param Property $property
     * @return void
     */
    public function delete(Property $property, Request $request){
        if ($this->isCsrfTokenValid('delete'. $property->getId(), $request->get('_token')))
        {
            $this->em->remove($property);
            $this->addFlash('success' , 'bien supprimé avec succèss');
           $this->em->flush();
        }
            return $this->redirectToRoute('admin.property.index');

        
    
    }
}
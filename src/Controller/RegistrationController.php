<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration" , name = "app_registration")
     */
     public function registration(Request $request , EntityManagerInterface $em , UserPasswordHasherInterface $passwordHasher ): Response
     {
       // $em = $this->getDoctrine()->getManager();
         $user = new User();
         $form = $this->createForm(RegistrationType::class, $user );
          
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid())
         {
             $hash = $passwordHasher->hashPassword($user, $user->getpassword());
             $user->setPassword($hash);
             $em->persist($user);
             $em->flush();
             $this->addFlash('success' ,'enregistrer avec succèss');
           return $this->redirectToRoute('home');
         }
           
         return $this->render("security/registration.html.twig" , [
            'property'=>$user,
            'form' => $form->createView()
        ]);
        
     }
}
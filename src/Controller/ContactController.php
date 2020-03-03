<?php

namespace App\Controller;

use DateTime;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     * @IsGranted("ROLE_USER")
     */
    public function index(ContactRepository $concactRep)
    {
        $curentUser = $this->getUser();

        $contacts = $concactRep->findBy(['user' => $curentUser], ['nom' => 'ASC', 'prenom' => 'ASC']);

        return $this->render('contact/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }
    
    /**
     * @Route("/contact/{id}", name="contact_view", requirements={"id"="\d+"})
     * @Security("is_granted('ROLE_USER') and contact.getUser() === user")
     */
    public function view(Contact $contact){
        
        return $this->render('contact/view.html.twig',
            [
                'contact' => $contact
            ]
        );

    }

     /**
     * @Route("/contact/delete/{id}", name="contact_delete", requirements={"id"="\d+"})
     * @Security("is_granted('ROLE_USER') and contact.getUser() === user")
     */
    public function delete(Contact $contact, EntityManagerInterface $manager){

        $manager->remove($contact);
        $manager->flush();

        return $this->redirectToRoute('contact');

    }
    
    
    /**
     * @Route ("/contact/add", name="contact_add")
     * @IsGranted("ROLE_USER")
     */
    public function add(Request $request, EntityManagerInterface $manager){
        
        $contact = new Contact;
        
        $form = $this->createForm(ContactType::class, $contact);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
            
            $contact->setUser($this->getUser());
            $date = new DateTime();
            $contact->setDateCreation($date);
            $contact->setDateModification($date);
            
            $manager->persist($contact);
            
            $manager->flush();
            
            return $this->redirectToRoute('contact');
            
        }
        
        
        return $this->render('contact/add.html.twig',
        [
            'form' => $form->createView()
            ]
        );
    }



    /**
     * @Route ("/contact/edit/{id}", name="contact_edit", requirements={"id"="\d+"})
     * @Security("is_granted('ROLE_USER') and contact.getUser() === user")
     */
    public function edit(Contact $contact, Request $request, EntityManagerInterface $manager){
                
        $form = $this->createForm(ContactType::class, $contact);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
            
            $contact->setUser($this->getUser());
            $date = new DateTime();
            $contact->setDateModification($date);
            
            $manager->persist($contact);
            
            $manager->flush();
            
            return $this->redirectToRoute('contact');
            
        }
        
        
        return $this->render('contact/edit.html.twig',
        [
            'form' => $form->createView()
            ]
        );
    }

    

}

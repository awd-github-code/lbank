<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Library;
use App\Form\LibraryType;
use App\Services\FileUploader;
use App\Repository\LibraryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LibraryController extends AbstractController
{
    /**
     * @Route("/library", name="library")
     */    
    public function index(LibraryRepository $libraryRepository, FileUploader $fileUploader): Response
    {                          
        return $this->render('library/list.html.twig', [
            "library" => $library
        ]);          
    }

    /**   
     * @Route("/library/new", name="library_create")    
     */   
    public function create(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $library = new Library();          
                   
        $form = $this->createForm(LibraryType::class, $library)->handleRequest($request);                                                  

        if ($form->isSubmitted() && $form->isValid()) {                            
            $entityManager->persist($library);  
            $entityManager->flush();    
                                                                            
            return $this->redirectToRoute("library");                            
        }       
        return $this->render('library/edit.html.twig', [
            'form' => $form->createView(),           
            'edit' => false
        ]);
    }      

    /**   
     * @Route("/library/{id}", name="library_update")    
     */   
    public function update(Request $request, Library $library, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {                                
        $form = $this->createForm(LibraryType::class, $library)->handleRequest($request);                                                  

        if ($form->isSubmitted() && $form->isValid()) {                                                                          
            $entityManager->flush();      

            return $this->redirectToRoute("library");                            
        }       

        return $this->render('library/edit.html.twig', [
            'form' => $form->createView(),           
            'edit' => true
        ]);
    }   
}
<?php

namespace App\Controller;

use App\Entity\Unit;
use App\Form\UnitType;
use App\Entity\Library;
use App\Form\LibraryType;
use App\Services\FileUploader;
use App\Repository\UnitRepository;
use App\Repository\LibraryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UnitController extends AbstractController
{    
    /**
     *
     * @Route("/library/{library_id}/unit", name="unit_list")
     */    
    public function index($library_id, UnitRepository $unitRepository, LibraryRepository $libraryRepository, FileUploader $fileUploader): Response
    {    
        
        $units = $unitRepository->findByLibrary($library_id);

        $library = $libraryRepository->findById($library_id);
  
        return $this->render('unit/list.html.twig', [
            'units' => $units,           
            'soundPath' => $fileUploader->getSoundDirectory2('units', $unit->getId()),          
            'library' => $library
        ]);          
    }
    /**   
     * @Route("/library/{library_id}/unit/new", name="unit_create")    
     */       
    public function create($library_id, Request $request, LibraryRepository $libraryRepository, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $unit = new Unit();                
                                 
        $form = $this->createForm(UnitType::class, $unit)->handleRequest($request);                                                  

        if ($form->isSubmitted() && $form->isValid()) {      

            $library = $libraryRepository->findOneBy(['id' => $library_id]);
            $unit->setLibrary($library);

            $entityManager->persist($unit);  
            $entityManager->flush();  
                                         
            $newSoundUpload = $form->get('uploadedSoundFile')->getData();                               
            // If sound was added
            if ($newSoundUpload) {
                $newFileName = $fileUploader->upload($newSoundUpload, 'mp3', 'units', $unit->getId());                
                $unit->setSound($newFileName);            
            }
           
            $entityManager->flush();              
                                          
            return $this->redirectToRoute('unit_list', ['library_id' => $library_id]);                            
        }       
        return $this->render('unit/edit.html.twig', [
            'form' => $form->createView(),                  
            'edit' => false,
            'library_id' => $library_id
        ]);
    }     

    /**   
     * @Route("/library/{library_id}/unit/{id}", name="unit_update")    
     */   
    public function update($id, $library_id, Request $request, Unit $unit, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {                                
        $form = $this->createForm(UnitType::class, $unit)->handleRequest($request);                                                  

        if ($form->isSubmitted() && $form->isValid()) {                             
            $newSoundUpload = $form->get('uploadedSoundFile')->getData();                                          
            // If sound was changed
            if ($newSoundUpload) {
                $newFileName = $fileUploader->upload($newSoundUpload, 'units', $unit->getId());                            
                $unit->setSound($newFileName);            
            }  
                        
            $entityManager->flush();                                               
            return $this->redirectToRoute('unit_list', ['library_id' => $library_id]);                            
        }       

        return $this->render('unit/edit.html.twig', [
            'form' => $form->createView(),         
            'edit' => true,
            'library_id' => $library_id           
        ]);
    }  
        
}
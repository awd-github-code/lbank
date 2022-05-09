<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Entity\Library;
use App\Form\LibraryType;
use App\Services\FileUploader;
use App\Repository\ItemRepository;
use App\Repository\UnitRepository;
use App\Repository\LibraryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ItemController extends AbstractController
{
    /**
     * @Route("/unit/{unit_id}/item", name="item_list")
     */    
    public function index($unit_id, UnitRepository $unitRepository, ItemRepository $lineRepository, FileUploader $fileUploader): Response    
    {                 
        $unit = $unitRepository->findOneById($unit_id);
        $library = $unit->getLibrary();       
        $items = $lineRepository->findByUnit($unit_id);
       
        return $this->render('item/list.html.twig', [
            'items' => $items,
            'unit' => $unit_id,
            'unitItem' => $unit,
            
            'soundPath' => $fileUploader->getSoundDirectory2(),
            'library' => $library            
        ]);          
    }

    /**   
     * @Route("/item/unit/{unit_id}/new", name="item_create")    
     */   
    public function create($unit_id, Request $request, UnitRepository $unitRepository, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $item = new Item();                
                                 
        $form = $this->createForm(ItemType::class, $item)->handleRequest($request);                                                  

        if ($form->isSubmitted() && $form->isValid()) {      

            $unit = $unitRepository->findOneBy(['id' => $unit_id]);
            $item->setUnit($unit);

            $entityManager->persist($item);  
            $entityManager->flush();    
                              
            $newSoundUpload = $form->get('uploadedSoundFile')->getData();                               
            // If sound was added
            if ($newSoundUpload) {
                $newFileName = $fileUploader->upload($newSoundUpload, 'units', $unit_id);                
                $unit->setSound($newFileName);            
            }
            
            $entityManager->flush();              
                                          
            return $this->redirectToRoute('item_list', ['unit_id' => $unit_id]);                            
        }       
        return $this->render('item/edit.html.twig', [
            'form' => $form->createView(),            
            'edit' => false,
            'unit' => $unit_id
        ]);
    }      

    /**   
     * @Route("/item/unit/{unit_id}/{id}", name="item_update")    
     */   
    public function update($id, $unit_id, Request $request, Item $item, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {                                
        $form = $this->createForm(ItemType::class, $item)->handleRequest($request);                                                  

        if ($form->isSubmitted() && $form->isValid()) {                               
            $newSoundUpload = $form->get('uploadedSoundFile')->getData();                                          
            // If sound was changed
            if ($newSoundUpload) {
                $newFileName = $fileUploader->upload($newSoundUpload, 'units', $unit_id);                            
                $unit->setSound($newFileName);            
            }  
                        
            $entityManager->flush();                                               
            return $this->redirectToRoute('item_list', ['unit_id' => $unit_id]);                            
        }       

        return $this->render('item/edit.html.twig', [
            'form' => $form->createView(),           
            'edit' => true,
            'unit' => $unit_id            
        ]);
    }      
}
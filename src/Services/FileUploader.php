<?php
namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{    
    private string $soundDirectory;
    private string $soundDirectory2;

    private $id;
    private $entity;

    public function __construct($soundDirectory, $soundDirectory2)
    {
        $this->soundDirectory = $soundDirectory;
        $this->soundDirectory2 = $soundDirectory2;
    }

    public function upload(UploadedFile $file, $entity, $id)
    {               
        $this->id = $id;
        $this->entity = $entity;

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);     
        $fileName = $originalFilename.'.'.$file->guessExtension();                   

        if (!file_exists($this->getSoundDirectory().'/'.$fileName)) {
            $file->move($this->getSoundDirectory(), $fileName);  
        }           
                                                  
        return $fileName;
    }   

    public function getSoundDirectory()
    {
        $dir = $this->soundDirectory.'/'.$this->entity.'/'.$this->id;
        if(!is_dir($dir)) {
            mkdir($dir, 0755);
        }
        return $dir;
    }

    public function getSoundDirectory2($entity, $id)
    {
        return $this->soundDirectory2.'/'.$entity.'/'.$id;       
    }
   
}

   
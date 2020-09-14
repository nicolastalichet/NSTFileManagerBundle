<?php
namespace App\NstFileManager\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class Uploader
{
    private $targetDirectory;
    private $slugger;

    public function __construct(
        string $targetDirectory, 
        SluggerInterface $slugger
    )
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file, string $path)
    {
        try {
            $originalFilename = pathinfo(
                $file->getClientOriginalName(), 
                PATHINFO_FILENAME
            );

            $safeFilename = $this->slugger->slug(
                $originalFilename
            );

            $fileName = $safeFilename
                .'-'
                .uniqid()
                .'.'
                .$file->guessExtension()
            ;

            $file->move(
                $this->getTargetDirectory(), 
                $fileName
            );
        } catch (FileException $e) {
            throw new Exception("Error Processing file move.", 1);
            
        }

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    /**
    * generate an uniq folder name.
    *
    * @param string prefix The folder prefix.
    * @param string suffix The folder suffix
    *
    * @return string The folder name.
    */
    public function generate uniqFolderName(
        string $prefix = '', 
        string $suffix = ''
    ): string
    {
        return string $prefix . uniqid() . $suffix;
    }
}
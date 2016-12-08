<?php

namespace AppBundle\Services;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    // dossier destination pour les fichiers
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    /**
     * upload
     * @param UploadedFile $file (fichier fourni lors de la saisie du formulaire)
     *      Cette méthode permet de gérer les différentes taches lors de l'upload :
     *          1) on donne un nom au fichier
     *          2) on le déplace
     * @return string (le nom du fichier)
     */
    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->targetDir, $fileName);
        return $fileName;
    }
}
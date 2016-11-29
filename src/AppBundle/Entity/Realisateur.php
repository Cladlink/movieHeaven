<?php
/**
 * Created by PhpStorm.
 * User: cladlink
 * Date: 28/11/16
 * Time: 10:17
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="Realisateur")
 */
class Realisateur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="idRealisateur")
     */
    private $idRealisateur;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", name="nomRealisateur")
     */
    private $nomRealisateur;


    public function __toString()
    {
        return $this->nomRealisateur;
    }

    /**
     * getters and setters
     */
    public function getIdRealisateur() { return $this->idRealisateur; }
    public function getNomRealisateur() { return $this->nomRealisateur; }
    public function setNomRealisateur($nomRealisateur) { $this->nomRealisateur = $nomRealisateur; }


}
<?php
/**
 * Created by PhpStorm.
 * User: cladlink
 * Date: 28/11/16
 * Time: 10:16
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="TypeFilm")
 * @UniqueEntity(fields={"libelleTypeFilm"}, message="Libellé déjà existant")
 */
class TypeFilm
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="idTypeFilm")
     */
    private $idTypeFilm;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", name="libelleTypeFilm")
     */
    private $libelleTypeFilm;

    /**
     * getters and setters
     */
    public function getIdTypeFilm() { return $this->idTypeFilm; }
    public function getLibelleTypeFilm() { return $this->libelleTypeFilm; }
    public function setLibelleTypeFilm($libelleTypeFilm) { $this->libelleTypeFilm = $libelleTypeFilm; }

}
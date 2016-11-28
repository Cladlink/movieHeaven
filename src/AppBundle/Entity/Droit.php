<?php
/**
 * Created by PhpStorm.
 * User: cladlink
 * Date: 28/11/16
 * Time: 10:13
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="Droit")
 * @UniqueEntity(fields={"libelleDroit"}, message="Libellé déjà existant")
 */
class Droit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="idDroit")
     */
    private $idDroit;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", name="libelleDroit")
     */
    private $libelleDroit;

    /**
     * getters and setters
     */
    public function getIdDroit() { return $this->idDroit; }
    public function getLibelleDroit() { return $this->libelleDroit; }
    public function setLibelleDroit($libelleDroit){ $this->libelleDroit = $libelleDroit; }


}
<?php
/**
 * Created by PhpStorm.
 * User: cladlink
 * Date: 28/11/16
 * Time: 10:15
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="EtatCommande")
 * @UniqueEntity(fields={"libelleCommande"}, message="Libellé déjà existant")
 */
class EtatCommande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="idEtatCommande")
     */
    private $idEtatCommande;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", name="libelleEtatCommande")
     */
    private $libelleEtatCommande;

    /**
     * getters and setters
     */
    public function getIdEtatCommande() { return $this->idEtatCommande; }
    public function getLibelleEtatCommande() { return $this->libelleEtatCommande; }
    public function setLibelleEtatCommande($libelleEtatCommande) { $this->libelleEtatCommande = $libelleEtatCommande; }
}
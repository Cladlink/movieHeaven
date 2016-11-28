<?php
/**
 * Created by PhpStorm.
 * User: cladlink
 * Date: 28/11/16
 * Time: 10:14
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="Commande")
 */
class Commande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="idCommande")
     */
    private $idCommande;

    /**
     * @Assert\Count(min="0", minMessage="Un prix doit être positif")
     * @Assert\NotBlank()
     * @ORM\Column(type="float", name="prixCommande")
     */
    private $prixCommande;

    /**
     * @Assert\NotBlank()
     * @Assert\Date()
     * @ORM\Column(type="date", name="dateCommande")
     */
    private $dateCommande;
    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumn(name="utilisateurId", referencedColumnName="idUtilisateur")
     */
    private $utilisateurId;

    /**
     * @ORM\ManyToOne(targetEntity="EtatCommande")
     * @ORM\JoinColumn(name="etatId", referencedColumnName="idEtatCommande")
     */
    private $etatId;

    /**
     * getters and setters
     */
    public function setIdCommande($idCommande) { $this->idCommande = $idCommande; }
    public function getPrixCommande() { return $this->prixCommande; }
    public function setPrixCommande($prixCommande) { $this->prixCommande = $prixCommande; }
    public function getDateCommande() { return $this->dateCommande; }
    public function setDateCommande($dateCommande) { $this->dateCommande = $dateCommande; }
    public function getUtilisateurId() { return $this->utilisateurId; }
    public function setUtilisateurId($utilisateurId) { $this->utilisateurId = $utilisateurId; }
    public function getEtatId() { return $this->etatId; }
    public function setEtatId($etatId) { $this->etatId = $etatId; }

}
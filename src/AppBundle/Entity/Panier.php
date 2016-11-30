<?php
/**
 * Created by PhpStorm.
 * User: cladlink
 * Date: 28/11/16
 * Time: 10:19
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="Panier")
 */
class Panier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="idPanier")
     */
    private $idPanier;
    /**
     * @Assert\NotBlank()
     * @Assert\Count(min="0", minMessage="Une quantité doit être positive")
     * @ORM\Column(type="integer", name="quantitePanier")
     */
    private $quantitePanier;

    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumn(name="utilisateurId", referencedColumnName="idUtilisateur")
     */
    private $utilisateurId;

    /**
     * @ORM\ManyToOne(targetEntity="Film")
     * @ORM\JoinColumn(name="filmId", referencedColumnName="idFilm")
     */
    private $filmId;


    /**
     * @ORM\ManyToOne(targetEntity="Commande")
     * @ORM\JoinColumn(name="commandeId", referencedColumnName="idCommande")
     */
    private $commandeId;



    /**
     * getters and setters
     */
    public function getIdPanier() { return $this->idPanier; }
    public function getQuantitePanier() { return $this->quantitePanier; }
    public function setQuantitePanier($quantitePanier) { $this->quantitePanier = $quantitePanier; }
    public function getUtilisateurId() { return $this->utilisateurId; }
    public function setUtilisateurId($utilisateurId) { $this->utilisateurId = $utilisateurId; }
    public function getFilmId() { return $this->filmId; }
    public function setFilmId($filmId) { $this->filmId = $filmId; }

}
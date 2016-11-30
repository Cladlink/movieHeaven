<?php
/**
 * Created by PhpStorm.
 * User: cladlink
 * Date: 30/11/16
 * Time: 12:58
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="commentaire_film")
 */
class CommentaireFilm
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $idCommentFilm;

    /**
     * @ORM\Column(type="text", name="commentaire")
     */
    private $commentaire;

    /**
     * @ORM\ManyToOne(targetEntity="Film")
     * @ORM\JoinColumn(name="filmId", referencedColumnName="idFilm")
     */
    private $filmId;

    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumn(name="utilisateurId", referencedColumnName="idUtilisateur")
     */
    private $utilisateurId;


    /**
     * Getters and setters
     */
    public function getIdCommentFilm() { return $this->idCommentFilm; }
    public function getCommentaire() { return $this->commentaire; }
    public function setCommentaire($commentaire) { $this->commentaire = $commentaire; }
    public function getFilmId() { return $this->filmId; }
    public function setFilmId($filmId) { $this->filmId = $filmId;}
    public function getUtilisateurId() { return $this->utilisateurId; }
    public function setUtilisateurId($utilisateurId) { $this->utilisateurId = $utilisateurId; }
}
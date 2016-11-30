<?php
/**
 * Created by PhpStorm.
 * User: cladlink
 * Date: 28/11/16
 * Time: 10:18
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="Film")
 * @UniqueEntity(fields={"imageFilm"}, message="Image déjà existante")
 */
class Film
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="idFilm")
     */
    private $idFilm;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", name="titreFilm")
     */
    private $titreFilm;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", name="dureeFilm")
     */
    private $dureeFilm;

    /**
     * @Assert\NotBlank()
     * @Assert\Date()
     * @ORM\Column(type="date", name="dateFilm")
     */
    private $dateFilm;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="float", scale=2, name="prixFilm")
     */
    private $prixFilm;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", name="quantiteFilm")
     */
    private $quantiteFilm;

    /**
     * @Assert\Image()
     * @ORM\Column(type="string", name="imageFilm")
     */
    private $imageFilm;

    /**
     * @ORM\ManyToOne(targetEntity="TypeFilm")
     * @JoinColumn(name="typeFilmId", referencedColumnName="idTypeFilm")
     */
    private $typeFilmId;

    /**
     * @ORM\ManyToOne(targetEntity="Realisateur")
     * @JoinColumn(name="realisateurId", referencedColumnName="idRealisateur")
     */
    private $realisateurId;

    /**
     * getters and setters
     */
    public function getIdFilm() { return $this->idFilm; }
    public function getTitreFilm(){ return $this->titreFilm; }
    public function setTitreFilm($titreFilm) { $this->titreFilm = $titreFilm; }
    public function getDureeFilm() { return $this->dureeFilm; }
    public function setDureeFilm($dureeFilm) { $this->dureeFilm = $dureeFilm; }
    public function getDateFilm() { return $this->dateFilm; }
    public function setDateFilm($dateFilm) { $this->dateFilm = $dateFilm; }
    public function getPrixFilm() { return $this->prixFilm; }
    public function setPrixFilm($prixFilm) { $this->prixFilm = $prixFilm; }
    public function getQuantiteFilm() { return $this->quantiteFilm; }
    public function setQuantiteFilm($quantiteFilm) { $this->quantiteFilm = $quantiteFilm; }
    public function getImageFilm() { return $this->imageFilm; }
    public function setImageFilm($imageFilm) { $this->imageFilm = $imageFilm; }
    public function getTypeFilmId() { return $this->typeFilmId; }
    public function setTypeFilmId($typeFilmId) { $this->typeFilmId = $typeFilmId; }
    public function getRealisateurId() { return $this->realisateurId; }
    public function setRealisateurId($realisateurId) { $this->realisateurId = $realisateurId; }


}
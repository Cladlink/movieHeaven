<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints as CaptchaAssert;

/**
 * @ORM\Entity
 * @ORM\Table(name="Utilisateur")
 * @UniqueEntity(fields={"emailUtilisateur"}, message="Cet email est déjà lié à un compte")
 */
class Utilisateur implements  UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", name="idUtilisateur")
     */
    private $idUtilisateur;

    /**
     * @ORM\Column(type="string", name="password")
     */
    private $password;
    /**
     *
     * @Assert\NotBlank()
     */
    private $plainPassword;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string", name="emailUtilisateur")
     */
    private $emailUtilisateur;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", name="nomUtilisateur")
     */
    private $nomUtilisateur;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", name="prenomUtilisateur")
     */
    private $prenomUtilisateur;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", name="adresseUtilisateur")
     */
    private $adresseUtilisateur;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", name="codePostalUtilisateur")
     */
    private $codePostalUtilisateur;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", name="VilleUtilisateur")
     */
    private $villeUtilisateur;
    /**
     * @ORM\Column(type="boolean", name="isActiveUtilisateur")
     */
    private $isActiveUtilisateur = false;

    /**
     * @ORM\Column(type="string", name="uniqueKeyUtilisateur")
     */
    private $uniqueKeyUtilisateur;

    /**
     * @ORM\Column(type="json_array", name="roles")
     */
    private $roles = ['ROLE_ADMIN'];

    /**
     * @CaptchaAssert\ValidCaptcha(
     *      message = "CAPTCHA validation failed, try again."
     * )
     */
    protected $captchaCode;

    public function __construct()
    {
        $this->uniqueKeyUtilisateur = uniqid();
    }


    /**
     * getters and setters
     */
    public function getIdUtilisateur() { return $this->idUtilisateur; }
    public function getEmailUtilisateur() { return $this->emailUtilisateur; }
    public function getNomUtilisateur() { return $this->nomUtilisateur; }
    public function getPrenomUtilisateur() { return $this->prenomUtilisateur; }
    public function getAdresseUtilisateur() { return $this->adresseUtilisateur; }
    public function getCodePostalUtilisateur() { return $this->codePostalUtilisateur; }
    public function getVilleUtilisateur() { return $this->villeUtilisateur; }
    public function getIsActiveUtilisateur() { return $this->isActiveUtilisateur; }
    public function getUniqueKeyUtilisateur() { return $this->uniqueKeyUtilisateur; }
    public function setEmailUtilisateur($emailUtilisateur) { $this->emailUtilisateur = $emailUtilisateur; }
    public function setNomUtilisateur($nomUtilisateur) { $this->nomUtilisateur = $nomUtilisateur; }
    public function setPrenomUtilisateur($prenomUtilisateur) { $this->prenomUtilisateur = $prenomUtilisateur; }
    public function setAdresseUtilisateur($adresseUtilisateur) { $this->adresseUtilisateur = $adresseUtilisateur; }
    public function setCodePostalUtilisateur($codePostalUtilisateur) { $this->codePostalUtilisateur = $codePostalUtilisateur; }
    public function setVilleUtilisateur($villeUtilisateur) { $this->villeUtilisateur = $villeUtilisateur; }
    public function setIsActiveUtilisateur($isActiveUtilisateur) { $this->isActiveUtilisateur = $isActiveUtilisateur; }
    public function setUniqueKeyUtilisateur($uniqueKeyUtilisateur) { $this->uniqueKeyUtilisateur = $uniqueKeyUtilisateur; }

    // gestions des droits
    public function setRoles($roles) { $this->roles = $roles; }
    public function getSalt() { }
    public function getUsername() { return $this->emailUtilisateur; }
    public function getRoles()
    {
        return $this->roles;
    }
    // cryptage des mots de passe
    public function getPassword() { return $this->password; }
    public function setPassword($password) { $this->password = $password; }
    public function getPlainPassword() { return $this->plainPassword; }
    public function setPlainPassword($plainPassword)
    {

        $this->plainPassword = $plainPassword;
        // guarentees that the entity looks "dirty" to doctrine
        // when changing the plainPassword
        $this->passwordUser = null;
    }
    public function eraseCredentials() { $this->plainPassword = null; }

    // captcha
    public function getCaptchaCode() { return $this->captchaCode; }
    public function setCaptchaCode($captchaCode) { $this->captchaCode = $captchaCode; }
}
<?php
/**
 * Created by PhpStorm.
 * User: cladlink
 * Date: 28/11/16
 * Time: 11:45
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class BoutiqueController extends Controller
{
    /**
     * @Route("/panier", name="afficherPanier")
     */
    public function afficherPanier(Utilisateur $utilisateur)
    {
        $em = $this->getDoctrine()->getManager();
        $contenu = $em->getRepository('AppBundle:Panier')
            ->findBy(array('utilisateurId' => $utilisateur->getIdUtilisateur()));
        return $this->render('admin/gestionFilms.html.twig', (['contenu' => $contenu]));
    }
}
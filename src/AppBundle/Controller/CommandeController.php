<?php
/**
 * Created by PhpStorm.
 * User: cladlink
 * Date: 29/11/16
 * Time: 22:19
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Utilisateur;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class CommandeController
 * @Route("/commande")
 */
class CommandeController extends Controller
{

    /**
     * @Route("/liste", name="listeCommandes")
     */
    public function afficherPanier(Utilisateur $utilisateur)
    {
        $em = $this->getDoctrine()->getManager();
        $contenu = $em->getRepository('AppBundle:Commande')
            ->findAll();
        return $this->render('admin/gestionFilms.html.twig', (['contenu' => $contenu]));
    }
}
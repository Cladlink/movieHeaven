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
     * @Route("/gestionCommandes", name="gestionCommandes")
     */
    public function gestionCommandes()
    {
        $em = $this->getDoctrine()->getManager();
        $commandes = $em->getRepository('AppBundle:Commande')->findAll();
        return $this->render('commande/gestionCommandes.html.twig', (['commandes' => $commandes]));
    }
}
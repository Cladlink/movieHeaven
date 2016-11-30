<?php
/**
 * Created by PhpStorm.
 * User: cladlink
 * Date: 29/11/16
 * Time: 22:19
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
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

    /**
     * @Route("/validerCommande/{idCommande}", name="validerCommande")
     */
    public function validerCommande($idCommande)
    {
        $em = $this->getDoctrine()->getManager();
        $commandeAValider = $em->getRepository('AppBundle:Commande')->findOneBy(['idCommande' => $idCommande]);
        $etat = $em->getRepository('AppBundle:EtatCommande')->findOneBy(['libelleEtatCommande' => 'Expediee']);
        $commandeAValider->setEtatId($etat);

        $em->persist($commandeAValider);
        $em->flush();

        $commandes = $em->getRepository('AppBundle:Commande')->findAll();
        return $this->render('commande/gestionCommandes.html.twig', (['commandes' => $commandes]));

    }

    /**
     * @Route("/ficheCommande/{idCommande}", name="ficheCommande")
     */
    public function ficheCommande($idCommande)
    {
        $em = $this->getDoctrine()->getManager();
        $paniersDeLaCommande = $em->getRepository('AppBundle:Panier')->findBy(['commandeId' => $idCommande]);
        return $this->render('commande/ficheCommande.html.twig', (['paniers' => $paniersDeLaCommande]));
    }
}
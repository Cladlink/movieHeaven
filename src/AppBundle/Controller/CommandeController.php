<?php
/**
 * Created by PhpStorm.
 * User: cladlink
 * Date: 29/11/16
 * Time: 22:19
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Commande;
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
     * @Route("/validerCommande/{commande}", name="validerCommande")
     */
    public function validerCommande(Commande $commande)
    {
        $em = $this->getDoctrine()->getManager();
        $etat = $em->getRepository('AppBundle:EtatCommande')->findOneBy(['libelleEtatCommande' => 'Expediee']);
        $commande->setEtatId($etat);

        $em->persist($commande);
        $em->flush();

        $commandes = $em->getRepository('AppBundle:Commande')->findAll();
        return $this->render('commande/gestionCommandes.html.twig', (['commandes' => $commandes]));

    }

    /**
     * @Route("/ficheCommande/{commande}", name="ficheCommande")
     */
    public function ficheCommande(Commande $commande)
    {
        $em = $this->getDoctrine()->getManager();
        $paniersDeLaCommande = $em->getRepository('AppBundle:Panier')->findBy(['commandeId' => $commande]);
        return $this->render('commande/ficheCommande.html.twig', (['paniers' => $paniersDeLaCommande]));
    }

    /**
     * @Route("/afficherCommandes", name="afficherCommandesUtilisateur")
     */
    public function afficherCommandesClient()
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur = $this->getUser();
        $etat1 = $em->getRepository('AppBundle:EtatCommande')->findOneBy(['libelleEtatCommande' => 'Expediee']);
        $etat2= $em->getRepository('AppBundle:EtatCommande')->findOneBy(['libelleEtatCommande' => 'En attente d expedition']);
        $etat3 = $em->getRepository('AppBundle:EtatCommande')->findOneBy(['libelleEtatCommande' => 'Livree']);
        $commandesUser = $em->getRepository('AppBundle:Commande')->findBy([
            'utilisateurId' => $utilisateur,
            'etatId' => [$etat1, $etat2, $etat3]
            ]);
        return $this->render('commande/afficherCommandeCoteUtilisateur.html.twig', ['commandes' => $commandesUser]);
    }
}
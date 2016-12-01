<?php
/**
 * Created by PhpStorm.
 * User: cladlink
 * Date: 28/11/16
 * Time: 11:43
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        $utilisateur = $this->getUser();
        $erreur = false;
        if($utilisateur != null)
        {
            $em = $this->getDoctrine()->getManager();
            $etat = $em->getRepository('AppBundle:EtatCommande')->findOneBy(['libelleEtatCommande' => 'Pas commandee']);
            $commandeEnCours = $em->getRepository('AppBundle:Commande')->findOneBy([
                'utilisateurId' => $utilisateur,
                'etatId' => $etat
            ]);
            if($commandeEnCours != null)
            {
                $dateActuelle = new \DateTime(date('Y-m-d'));
                $datePanier = $commandeEnCours->getDateCommande();
                $dateButoir = (new \DateTime(($datePanier->format('Y-m-d')).'+5 days'));
                $diff = $dateButoir->diff($dateActuelle);
                $diffString = $diff->format('%R%a days');
                if(str_split($diffString)[0] == '+')
                {
                    $paniersConcernes = $em->getRepository('AppBundle:Panier')->findBy([
                        'commandeId' => $commandeEnCours
                    ]);
                    foreach ($paniersConcernes as $key => $panier)
                    {
                        $em->remove($panier);
                        $em->flush();
                    }
                    $em->remove($commandeEnCours);
                    $em->flush();
                    $erreur = "Votre panier a ete supprime car cela faisait trop longtemps que vous n etes pas venu.";
                }

            }
        }
        return $this->render('index.html.twig', (['erreur' => $erreur]));
    }
}
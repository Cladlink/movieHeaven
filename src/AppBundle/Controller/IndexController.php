<?php
/**
 * Created by PhpStorm.
 * User: cladlink
 * Date: 28/11/16
 * Time: 11:43
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Utilisateur;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        /**
         * @var $utilisateur Utilisateur
         */
        $utilisateur = $this->getUser();
        $erreur = false;
        $em = $this->getDoctrine()->getManager();
        if($utilisateur != null)
        {
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
        if($utilisateur!= null && $utilisateur->getRoles() == ['ROLE_ADMIN'])
        {
            $films = $em->getRepository('AppBundle:Film')->findBy(array(), array('idFilm' => 'DESC'), 5);
        }
        else{
            $films = null;
        }




        return $this->render('index.html.twig', (['erreur' => $erreur, 'films' => $films]));
    }

}
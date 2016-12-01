<?php
/**
 * Created by PhpStorm.
 * User: cladlink
 * Date: 28/11/16
 * Time: 11:45
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Commande;
use AppBundle\Entity\CommentaireFilm;
use AppBundle\Entity\Film;
use AppBundle\Entity\Panier;
use AppBundle\Entity\Utilisateur;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/boutique")
 */
class BoutiqueController extends Controller
{
    /**
     * @Route("/ficheFilm/{idFilm}", name="ficheFilm")
     */
     public function ficheFilmAction(Film $film)
     {
         $em = $this->getDoctrine()->getManager();
         $films = $em->getRepository('AppBundle:Film')
             ->find($film);
         return $this->render(':Boutique:ficheFilm.html.twig', (['films' => $films]));
     }

    /**
     * @Route("/", name="afficherBoutique")
     */
    public function afficherBoutique()
    {
        $em = $this->getDoctrine()->getManager();
        $films = $em->getRepository('AppBundle:Film')->findAll();
        return $this->render('Boutique/boutique.html.twig', (['films' => $films]));
    }

    /**
     * @Route("/panier", name="afficherPanier")
     */
    public function afficherPanier()
    {

        $em = $this->getDoctrine()->getManager();
        $utilisateur = $this->getUser();
        $etat = $em->getRepository('AppBundle:EtatCommande')
            ->findOneBy(['libelleEtatCommande' => 'Pas commandee']);
        $commandeConcernee = $em->getRepository('AppBundle:Commande')
            ->findOneBy(['utilisateurId' => $utilisateur, 'etatId' => $etat]);
        $contenu = $em->getRepository('AppBundle:Panier')
            ->findBy(['utilisateurId' => $utilisateur, 'commandeId' => $commandeConcernee]);
        return $this->render('Boutique/panier.html.twig', (['contenu' => $contenu]));
    }

    /**
     * @Route("/ajouterPanier/{film}", name="ajouterPanier")
     */
    public function ajouterAuPanier(Film $film)
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur = $this->getUser();
        $etat = $em->getRepository('AppBundle:EtatCommande')
            ->findOneBy(['libelleEtatCommande' => 'Pas commandee']);
        $commandeConcernee = $em->getRepository('AppBundle:Commande')
            ->findOneBy(['utilisateurId' => $utilisateur, 'etatId' => $etat]);
        if(!$commandeConcernee)
        {
            $commandeConcernee = new Commande();
            $commandeConcernee->setUtilisateurId($utilisateur);
            $dateActuelle = new \DateTime(date('Y-m-d'));
            $commandeConcernee->setDateCommande($dateActuelle);
            $commandeConcernee->setEtatId($etat);
            $commandeConcernee->setPrixCommande(0);
            $em->persist($commandeConcernee);
            $em->flush();
        }
        $filmAAjouter = $em->getRepository('AppBundle:Film')->findOneBy(['idFilm' => $film]);
        $paniers = $em->getRepository('AppBundle:Panier')
            ->findBy(['utilisateurId' => $utilisateur->getIdUtilisateur()]);
        foreach ($paniers as $key => $panier)
        {
            if($panier->getFilmId() == $filmAAjouter && $panier->getCommandeId() == $commandeConcernee)
            {
                if($panier->getQuantitePanier()+1 > $filmAAjouter->getQuantiteFilm())
                {
                    $films = $em->getRepository('AppBundle:Film')->findAll();
                    return $this->render('Boutique/boutique.html.twig', ([
                        'films' => $films,
                        'erreur' => "Plus assez d objets en stock"
                    ]));
                }
                $panier->setQuantitePanier($panier->getQuantitePanier()+1);
                $em->persist($panier);
                $em->flush();
                $films = $em->getRepository('AppBundle:Film')->findAll();
                return $this->render('Boutique/boutique.html.twig', ['films' => $films]);
            }
        }
        $films = $em->getRepository('AppBundle:Film')->findAll();
        if($filmAAjouter->getQuantiteFilm() > 0)
        {
            $liaison = new Panier();
            $liaison->setQuantitePanier(1);
            $liaison->setFilmId($filmAAjouter);
            $liaison->setUtilisateurId($utilisateur);
            $liaison->setCommandeId($commandeConcernee);

            $em->persist($liaison);
            $em->flush();
            return $this->render('Boutique/boutique.html.twig', (['films' => $films]));
        }
        return $this->render('Boutique/boutique.html.twig',(['films' => $films, 'erreur' => "Plus assez d objets en stock"]));
    }

    /**
     * @Route("/panier/commander", name="commander")
     */
    public function commander()
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur = $this->getUser();
        $etat = $em->getRepository('AppBundle:EtatCommande')
            ->findOneBy(['libelleEtatCommande' => 'Pas commandee']);
        $commande = $em->getRepository('AppBundle:Commande')
            ->findOneBy(['utilisateurId' => $utilisateur, 'etatId' => $etat]);
        if($commande != null)
        {
            $erreur = false;
            // Calcul du montant total de la commande
            $paniers = $em->getRepository('AppBundle:Panier')
                ->findBy(['utilisateurId' => $utilisateur->getIdUtilisateur(), 'commandeId' => $commande]);
            if($paniers == null)
            {
                $films = $em->getRepository('AppBundle:Film')->findAll();
                return $this->render('Boutique/boutique.html.twig', (['films' => $films, 'erreur' => "La commande est vide"]));
            }
            $prixTotal = 0;
            foreach ($paniers as $key => $panier)
            {
                /**
                 * @var Panier $panier
                 */
                $panier->setCommandeId($commande);
                $film = $em->getRepository('AppBundle:Film')->findOneBy(['idFilm' => $panier->getFilmId()]);
                $quantite = $panier->getQuantitePanier();
                $prix = $film->getPrixFilm();
                $film->setQuantiteFilm($film->getQuantiteFilm()-$quantite);
                $em->persist($film);
                $em->flush();
                $prixTotal += $prix * $quantite;
            }
            $commande->setPrixCommande($prixTotal);
            $etatFait = $em->getRepository('AppBundle:EtatCommande')
                ->findOneBy(['libelleEtatCommande' => 'En attente d expedition']);
            $commande->setEtatId($etatFait);

            $em->persist($commande);
            $em->flush();
        }
        else
        {
            $erreur = "La commande est vide";
        }

        $films = $em->getRepository('AppBundle:Film')->findAll();
        return $this->render('Boutique/boutique.html.twig', (['films' => $films, 'erreur' => $erreur]));
    }
    /**
     * @Route("/commentFilm/{name}/notes", name="commentFilm")
     * @Method("GET")
     */
    public function getNotesAction(CommentaireFilm $commentsFilm)
    {
        /**
         * @var CommentaireFilm[] $notes
         */
        $notes = [];

        foreach ($notes as $note)
        {
            $notes[] = [
                'username' => $note->getUtilisateurId(),
                'note' => $note->getCommentaire()
            ];
        }

        $data = [
            'notes' => $notes
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/panier/plusQuantite/{idFilm}", name="plus")
     */
    public function augmenterQuantite($idFilm)
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur = $this->getUser();
        $etat = $em->getRepository('AppBundle:EtatCommande')
            ->findOneBy(['libelleEtatCommande' => 'Pas commandee']);
        $commande = $em->getRepository('AppBundle:Commande')
            ->findOneBy(['etatId' => $etat, 'utilisateurId' => $utilisateur]);
        $film = $em->getRepository('AppBundle:Film')
            ->findOneBy(['idFilm' => $idFilm]);
        $panier = $em->getRepository('AppBundle:Panier')->findOneBy([
            'filmId' => $film,
            'utilisateurId' => $utilisateur,
            'commandeId' => $commande
        ]);

        $quantiteActuelle = $panier->getQuantitePanier();
        if(($quantiteActuelle+1) > $film->getQuantiteFilm())
        {
            $contenu = $em->getRepository('AppBundle:Panier')
                ->findBy(['utilisateurId' => $utilisateur, 'commandeId' => $commande]);
            return $this->render('Boutique/panier.html.twig', (['contenu' => $contenu, 'erreur' => "Plus assez d objets en stock"]));
        }

        $panier->setQuantitePanier($quantiteActuelle+1);
        $em->persist($panier);
        $em->flush();


        $contenu = $em->getRepository('AppBundle:Panier')
            ->findBy(['utilisateurId' => $utilisateur, 'commandeId' => $commande]);
        return $this->render('Boutique/panier.html.twig', (['contenu' => $contenu]));
    }

    /**
     * @Route("/panier/moinsQuantite/{idFilm}", name="moins")
     */
    public function reduireQuantite($idFilm)
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur = $this->getUser();
        $etat = $em->getRepository('AppBundle:EtatCommande')
            ->findOneBy(['libelleEtatCommande' => 'Pas commandee']);
        $commande = $em->getRepository('AppBundle:Commande')
            ->findOneBy(['etatId' => $etat, 'utilisateurId' => $utilisateur]);
        $film = $em->getRepository('AppBundle:Film')
            ->findOneBy(['idFilm' => $idFilm]);
        $panier = $em->getRepository('AppBundle:Panier')->findOneBy([
            'filmId' => $film,
            'utilisateurId' => $utilisateur,
            'commandeId' => $commande
        ]);

        $quantiteActuelle = $panier->getQuantitePanier();
        if(($quantiteActuelle-1) < 1)
        {
            $em->remove($panier);
            $em->flush();

            $contenu = $em->getRepository('AppBundle:Panier')
                ->findBy(['utilisateurId' => $utilisateur, 'commandeId' => $commande]);
            return $this->render('Boutique/panier.html.twig', (['contenu' => $contenu]));
        }

        $panier->setQuantitePanier($quantiteActuelle-1);
        $em->persist($panier);
        $em->flush();


        $contenu = $em->getRepository('AppBundle:Panier')
            ->findBy(['utilisateurId' => $utilisateur, 'commandeId' => $commande]);
        return $this->render('Boutique/panier.html.twig', (['contenu' => $contenu]));
    }

    /**
     * @Route("/panier/supprimer/{film}", name="supprimerDuPanier")
     */
    public function supprimerDuPanier(Film $film)
    {
        $erreur = false;
        $em = $this->getDoctrine()->getManager();
        $utilisateur = $this->getUser();
        $etat = $em->getRepository('AppBundle:EtatCommande')
            ->findOneBy(['libelleEtatCommande' => 'Pas commandee']);
        $commande = $em->getRepository('AppBundle:Commande')
            ->findOneBy(['etatId' => $etat, 'utilisateurId' => $utilisateur]);
        $panierASupprimer = $em->getRepository('AppBundle:Panier')->findOneBy([
            'utilisateurId' => $utilisateur,
            'commandeId' => $commande,
            'filmId' => $film
        ]);

        if($panierASupprimer == null)
            $erreur = "Ce produit n est pas dans votre panier";

        $em->remove($panierASupprimer);
        $em->flush();

        $contenu = $em->getRepository('AppBundle:Panier')
            ->findBy(['utilisateurId' => $utilisateur, 'commandeId' => $commande]);
        return $this->render('Boutique/panier.html.twig', (['contenu' => $contenu, 'erreur' => $erreur]));
    }
}
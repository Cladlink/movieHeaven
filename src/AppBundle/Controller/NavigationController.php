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
use AppBundle\Form\CommentForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/boutique")
 */
class NavigationController extends Controller
{
    /**
     * @Route("/ficheFilm/{idFilm}", name="ficheFilm")
     */
     public function ficheFilmAction(Request $request, Film $idFilm)
     {
         if($this->getUser() == null)
         {
             $response = new Response();
             $request = Request::createFromGlobals();
             if($request->cookies->get('DerniersFilmsConsultes')!= null)
             {
                 $tab = $request->cookies->get('DerniersFilmsConsultes');
                 $tabDecoupe = explode(" ", $tab);
                 $isPresent = false;
                 for ($i = 0; $i < 5; $i++)
                     if ($tabDecoupe[$i] == $idFilm->getIdFilm()) {
                         $isPresent = true;
                         $place = $i - 1;
                     }
                 if (!$isPresent)
                     $place = 3;

                 for ($i = $place; $i >= 0; $i--)
                     if ($tabDecoupe[$i] != "-1")
                         $tabDecoupe[$i + 1] = $tabDecoupe[$i];

                 $tab = $idFilm->getIdFilm() . " "
                     . $tabDecoupe[1] . " "
                     . $tabDecoupe[2] . " "
                     . $tabDecoupe[3] . " "
                     . $tabDecoupe[4];
             }
             else
             {
                 $tab = $idFilm->getIdFilm()." -1 -1 -1 -1";
             }


             $cookie_info = array(
                 'name'  => 'DerniersFilmsConsultes',
                 'value' => $tab);

             $cookie = new Cookie($cookie_info['name'], $cookie_info['value']);

             $response->headers->setCookie($cookie);
             $response->send();
         }


         $em = $this->getDoctrine()->getManager();

         $form = $this->createForm(CommentForm::class);
         $form->handleRequest($request);
         if ($form->isSubmitted() && $form->isValid())
         {
             $commentairefilm = $form->getData()['commentaire'];
             $nouveauCommentaireFilm = new CommentaireFilm();
             $nouveauCommentaireFilm->setFilmId($idFilm);
             $nouveauCommentaireFilm->setUtilisateurId($this->getUser());
             $nouveauCommentaireFilm->setCommentaire($commentairefilm);

             $em->persist($nouveauCommentaireFilm);
             $em->flush();

             return $this->redirect($this->generateUrl('afficherBoutique'));
         }

         $commentaires = $em->getRepository('AppBundle:CommentaireFilm')->findBy([
             'filmId' => $idFilm
         ]);

         return $this->render(
             ':Navigation:ficheFilm.html.twig', array(
             'form' => $form->createView(),
             'films' => $idFilm,
             'commentaires' => $commentaires

       ));
     }

    /**
     * @Route("/", name="afficherBoutique")
     */
    public function afficherBoutique()
    {

        $em = $this->getDoctrine()->getManager();
        $films = $em->getRepository('AppBundle:Film')->findAll();
        $typeFilm = $em->getRepository('AppBundle:TypeFilm')->findAll();
        return $this->render(':Navigation:boutique.html.twig', (['films' => $films, 'typeFilm' => $typeFilm]));
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
        return $this->render(':Navigation:panier.html.twig', (['contenu' => $contenu]));
    }

    /**
     * @Route("/ajouterPanier/{film}", name="ajouterPanier")
     */
    public function ajouterAuPanier(Film $film)
    {
        $em = $this->getDoctrine()->getManager();
        $typeFilm = $em->getRepository('AppBundle:TypeFilm')->findAll();
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
                    return $this->render(':Navigation:boutique.html.twig', ([
                        'films' => $films,
                        'erreur' => "Plus assez d objets en stock",
                        'typeFilm' => $typeFilm
                    ]));
                }
                $panier->setQuantitePanier($panier->getQuantitePanier()+1);
                $em->persist($panier);
                $em->flush();
                $films = $em->getRepository('AppBundle:Film')->findAll();
                return $this->render(':Navigation:boutique.html.twig', ['films' => $films, 'typeFilm' => $typeFilm]);
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
            return $this->render(':Navigation:boutique.html.twig', (['films' => $films, 'typeFilm' => $typeFilm]));
        }

        return $this->render(':Navigation:boutique.html.twig',(['films' => $films, 'erreur' => "Plus assez d objets en stock", 'typeFilm' => $typeFilm]));
    }

    /**
     * @Route("/panier/commander", name="commander")
     */
    public function commander()
    {
        $em = $this->getDoctrine()->getManager();
        $typeFilm = $em->getRepository('AppBundle:TypeFilm')->findAll();
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
                return $this->render(':Navigation:boutique.html.twig', (['films' => $films, 'erreur' => "La commande est vide", 'typeFilm' => $typeFilm]));
            }
            $prixTotal = 0;
            foreach ($paniers as $key => $panier)
            {
                /**
                 * @var Panier $panier
                 */
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
        return $this->render(':Navigation:boutique.html.twig', (['films' => $films, 'erreur' => $erreur, 'typeFilm' => $typeFilm]));
    }

    /**
     * @Route("/panier/plusQuantite/{film}", name="plus")
     */
    public function augmenterQuantite(Film $film)
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur = $this->getUser();
        $etat = $em->getRepository('AppBundle:EtatCommande')
            ->findOneBy(['libelleEtatCommande' => 'Pas commandee']);
        $commande = $em->getRepository('AppBundle:Commande')
            ->findOneBy(['etatId' => $etat, 'utilisateurId' => $utilisateur]);
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
            return $this->render(':Navigation:panier.html.twig', (['contenu' => $contenu, 'erreur' => "Plus assez d objets en stock"]));
        }

        $panier->setQuantitePanier($quantiteActuelle+1);
        $em->persist($panier);
        $em->flush();


        $contenu = $em->getRepository('AppBundle:Panier')
            ->findBy(['utilisateurId' => $utilisateur, 'commandeId' => $commande]);
        return $this->render(':Navigation:panier.html.twig', (['contenu' => $contenu]));
    }

    /**
     * @Route("/panier/moinsQuantite/{film}", name="moins")
     */
    public function reduireQuantite(Film $film)
    {
        $em = $this->getDoctrine()->getManager();
        $utilisateur = $this->getUser();
        $etat = $em->getRepository('AppBundle:EtatCommande')
            ->findOneBy(['libelleEtatCommande' => 'Pas commandee']);
        $commande = $em->getRepository('AppBundle:Commande')
            ->findOneBy(['etatId' => $etat, 'utilisateurId' => $utilisateur]);
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
            return $this->render(':Navigation:panier.html.twig', (['contenu' => $contenu]));
        }

        $panier->setQuantitePanier($quantiteActuelle-1);
        $em->persist($panier);
        $em->flush();


        $contenu = $em->getRepository('AppBundle:Panier')
            ->findBy(['utilisateurId' => $utilisateur, 'commandeId' => $commande]);
        return $this->render(':Navigation:panier.html.twig', (['contenu' => $contenu]));
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
        return $this->render(':Navigation:panier.html.twig', (['contenu' => $contenu, 'erreur' => $erreur]));
    }
}
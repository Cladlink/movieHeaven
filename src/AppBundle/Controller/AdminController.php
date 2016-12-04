<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Film;
use AppBundle\Form\addFilmForm;
use AppBundle\Form\addRealisateurForm;
use AppBundle\Form\addTypeFilmForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="gestion")
     */
    public function accueilGestion()
    {
        return $this->render('admin/accueilGestion.html.twig');
    }

    /**
     * @Route("/gestionFilms", name="gestionFilms")
     */
    public function gestionFilms()
    {
        $em = $this->getDoctrine()->getManager();
        $films = $em->getRepository('AppBundle:Film')->findAll();
        return $this->render('admin/gestionFilms.html.twig', (['films' => $films]));
    }

    /**
     * @Route("/gestionRealisateurs", name="gestionRealisateurs")
     */
    public function gestionRealisateur()
    {
        $em = $this->getDoctrine()->getManager();
        $realisateurs = $em->getRepository('AppBundle:Realisateur')->findAll();
        return $this->render('admin/gestionRealisateurs.html.twig', (['realisateurs' => $realisateurs]));
    }

    /**
     * @Route("/gestionTypesFilm", name="gestionTypesFilm")
     */
    public function gestionTypesFilm()
    {
        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository('AppBundle:TypeFilm')->findAll();
        return $this->render('admin/gestionTypesFilm.html.twig', (['types' => $types]));
    }

    /**
     * @Route("/nouveauFilm", name="ajouterNouveauFilm")
     */
    public function nouveauFilm(Request $request)
    {
        $form = $this->createForm(addFilmForm::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            /**
             * @var Film $film
             */
            $film = $form->getData();

            /** @var UploadedFile $file */
            $file = $film->getImageFilm();
            $fileName = $film->getTitreFilm().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );
            $film->setImageFilm($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($film);
            $em->flush();

            return $this->redirectToRoute('gestionFilms');
        }
        return $this->render('admin/addNouveauFilm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/nouveauRealisateur", name="ajouterNouveauRealisateur")
     */
    public function nouveauRealisateur(Request $request)
    {
        $form = $this->createForm(addRealisateurForm::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $realisateur = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($realisateur);
            $em->flush();

            return $this->redirectToRoute('gestionRealisateurs');
        }
        return $this->render('admin/addNouveauRealisateur.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/nouveauTypeFilm", name="ajouterNouveauType")
     */
    public function nouveauType(Request $request)
    {
        $form = $this->createForm(addTypeFilmForm::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $type = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($type);
            $em->flush();

            return $this->redirectToRoute('gestionTypesFilm');
        }
        return $this->render('admin/addNouveauTypeFilm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/supprimerFilmBDD/{film}", name="suppFilmBDD")
     */
    public function supprimerFilm(Film $film)
    {
        $em = $this->getDoctrine()->getManager();
        $paniersRelieFilm = $em->getRepository('AppBundle:Panier')->findBy([
            'filmId' => $film
        ]);
        if($paniersRelieFilm == null)
        {
            $erreur = false;
            $em->remove($film);
            $em->flush();
        }
        else
        {
            $erreur = "Ce film est present dans le panier d un utilisateur. Impossible de le supprimer.";
        }
        $films = $em->getRepository('AppBundle:Film')->findAll();
        return $this->render('admin/gestionFilms.html.twig', (['films' => $films, 'erreur' => $erreur]));
    }
}
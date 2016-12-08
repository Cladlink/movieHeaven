<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Film;
use AppBundle\Entity\Realisateur;
use AppBundle\Form\addFilmForm;
use AppBundle\Form\AddQuantiteFilm;
use AppBundle\Form\addRealisateurForm;
use AppBundle\Form\addTypeFilmForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
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
        return $this->render(':admin/FilmManagement:gestionFilms.html.twig', (['films' => $films]));
    }

    /**
     * @Route("/gestionRealisateurs", name="gestionRealisateurs")
     */
    public function gestionRealisateur()
    {
        $em = $this->getDoctrine()->getManager();
        $realisateurs = $em->getRepository('AppBundle:Realisateur')->findAll();
        return $this->render(':admin/DirectorManagement:gestionRealisateurs.html.twig', (['realisateurs' => $realisateurs]));
    }

    /**
     * @Route("/gestionTypesFilm", name="gestionTypesFilm")
     */
    public function gestionTypesFilm()
    {
        $em = $this->getDoctrine()->getManager();
        $types = $em->getRepository('AppBundle:TypeFilm')->findAll();
        return $this->render(':admin/TypeFilmManagement:gestionTypesFilm.html.twig', (['types' => $types]));
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
        return $this->render(':admin/FilmManagement:addNouveauFilm.html.twig', [
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
        return $this->render('admin/DirectorManagement/addNouveauRealisateur.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/editRealisateur/{id}", name="editRealisateur")
     */
     public function editRealisateurAction(Realisateur $realisateur, Request $request)
     {
         $form = $this->createForm(addRealisateurForm::class, $realisateur);

         $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid())
         {
             $realisateur = $form->getData();

             $em = $this->getDoctrine()->getManager();
             $em->persist($realisateur);
             $em->flush();

             return $this->redirectToRoute('gestionRealisateurs');
         }
         return $this->render('admin/DirectorManagement/addNouveauRealisateur.html.twig', [
             'form' => $form->createView(),
             'realisateur' => $realisateur
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
        return $this->render('admin/TypeFilmManagement/addNouveauTypeFilm.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/modifierFilm/{film}", name="modifierFilm")
     */
    public function modifierFilm(Request $request, Film $film)
    {
        $imageName = new File($this->getParameter('images_directory').'/'.$film->getImageFilm());
        $film->setImageFilm(
            $imageName
        );

        $form = $this->createForm(addFilmForm::class, $film);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if($form->isSubmitted() && $form->isValid())
        {
            /**
             * @var Film $film
             */
            $filmEdited = $form->getData();

            if ($filmEdited->getImageFilm() == null)
            {
                $filmEdited->setImageFilm($imageName);
            }
            /** @var UploadedFile $file */
            $file = $filmEdited->getImageFilm();
            $fileName = $filmEdited->getTitreFilm().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );
            $filmEdited->setImageFilm($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($filmEdited);
            $em->flush();

            return $this->redirectToRoute('gestionFilms');
        }
        return $this->render(':admin/FilmManagement:editFilm.html.twig',
            array('form' => $form->createView(),
                'films' => $film));
    }
}
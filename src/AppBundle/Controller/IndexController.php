<?php
/**
 * Created by PhpStorm.
 * User: cladlink
 * Date: 28/11/16
 * Time: 11:43
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Film;
use AppBundle\Form\AddQuantiteFilm;
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
        if ($utilisateur!= null &&
            $utilisateur->getRoles() == ['ROLE_ADMIN'])
            return $this->indexAdminAction();
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/indexAdmin", name="indexAdmin")
     */
    public function indexAdminAction()
    {
        $em = $this->getDoctrine()->getManager();
        $films = $em->getRepository('AppBundle:Film')->findBy( array('quantiteFilm' => 0));

        return $this->render(':admin:indexAdmin.html.twig',
            array('films' => $films));
    }
    /**
     * @Route("/indexAdmin/{id}/{quantiteFilm}", name="indexAddAdmin")
     */
    public function indexAdminAddAction(Film $film, $quantiteFilm)
    {
            //todo modifier le nbQuantite
            $em = $this->getDoctrine()->getManager();
            $film->setQuantiteFilm($quantiteFilm);
            $em->persist($film);
            $em->flush();
        return $this->indexAdminAction();
    }
}
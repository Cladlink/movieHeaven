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
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        /*
         * 1 - Je regarde si le cookie existe
            2 - Si oui je récupère son contenu
               2.1 - J'ajoute à la variable le nouvel identifiant
               2.2 - Je recré le même cookie avec la nouvelle variable pour écraser l'ancien
            3 - Sinon
               3.1 - Je créé le cookie avec le premier identifiant
               3.2 - Je renviens au 2)*/

        $response = new Response();
        $request = Request::createFromGlobals();
        $tab = 0;
        if($request->cookies->get('DerniersFilmsConsultes'))
        {
            $tab = $request->cookies->get('DerniersFilmsConsultes')+1;
        }
        $cookie_info = array(
            'name'  => 'DerniersFilmsConsultes',
            'value' => $tab);

        $cookie = new Cookie($cookie_info['name'], $cookie_info['value']);

        $response->headers->setCookie($cookie);
        $response->send();

        $utilisateur = $this->getUser();
        if ($utilisateur!= null &&
            $utilisateur->getRoles() == ['ROLE_ADMIN'])
            return $this->indexAdminAction();
        return $this->render('index.html.twig',array(),$response);
    }

    /**
     * @Route("/indexAdmin", name="indexAdmin")
     */
    public function indexAdminAction()
    {
        $em = $this->getDoctrine()->getManager();
        $films = $em->getRepository('AppBundle:Film')->findBy( array('quantiteFilm' => 0));

        return $this->render('indexAdmin.html.twig',
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


    /*public function cookieAction($flag)
    {
        $response = new Response();
        $request = Request::createFromGlobals();


        $cookie_info = array(
            'name'  => 'DerniersFilmsConsultes',
            'value' => $flag);

        $cookie = new Cookie($cookie_info['name'], $cookie_info['value']);

        //on gere si le cookie existe :
        if( $request->cookies->get('DerniersFilmsConsultes') )
        {

            //var_dump($response->headers->clearCookie('DerniersFilmsConsultes'));
        }

        $response->headers->setCookie($cookie);
        $response->send();
        return $this->render(':Navigation:boutique.html.twig', array(),$response);

    }*/
}
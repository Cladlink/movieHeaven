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
        $utilisateur = $this->getUser();
        if ($utilisateur!= null &&
            $utilisateur->getRoles() == ['ROLE_ADMIN'])
            return $this->indexAdminAction();
        elseif($utilisateur!= null &&
            $utilisateur->getRoles() == ['ROLE_USER'])
            return $this->indexUserAction();
        $em = $this->getDoctrine()->getManager();
        $response = new Response();
        $request = Request::createFromGlobals();
        $derniersConsultes = array();
        if($request->cookies->get('DerniersFilmsConsultes')!= null)
        {
            $tab = $request->cookies->get('DerniersFilmsConsultes');
            $tabDecoupe = explode(" ", $tab);
            $i = 0;
            while($i<5 && $tabDecoupe[$i] != "-1")
            {
                $derniersConsultes[$i] = $em->getRepository('AppBundle:Film')->findOneBy(['idFilm' => $tabDecoupe[$i]]);
                $i++;
            }
        }
        else
        {
            $tab = "-1 -1 -1 -1 -1";
        }
        $cookie_info = array(
            'name'  => 'DerniersFilmsConsultes',
            'value' => $tab);

        $cookie = new Cookie($cookie_info['name'], $cookie_info['value']);

        $response->headers->setCookie($cookie);
        $response->send();


        return $this->render('index.html.twig', ['films' => $derniersConsultes]);
    }

    /**
     * @Route("/indexAdmin", name="indexAdmin")
     */
    public function indexAdminAction()
    {
        $em = $this->getDoctrine()->getManager();
        $films = $em->getRepository('AppBundle:Film')->findBy( array('quantiteFilm' => 0));

        return $this->render('indexAdmin.html.twig', array('films' => $films));
    }
    /**
     * @Route("/indexAdmin/{id}/{quantiteFilm}", name="indexAddAdmin")
     */
    public function indexAdminAddAction(Film $film, $quantiteFilm)
    {
        $em = $this->getDoctrine()->getManager();
        $film->setQuantiteFilm($quantiteFilm);
        $em->persist($film);
        $em->flush();
        return $this->indexAdminAction();
    }

    /**
     * @Route("/indexUser", name="indexAddAdmin")
     */
    public function indexUserAction()
    {
        $em = $this->getDoctrine()->getManager();
        $films = $em->getRepository('AppBundle:Film')->findby(array(), array('idFilm' => 'DESC'), 5);
        $typeFilm = $em->getRepository('AppBundle:TypeFilm')->findAll();
        return $this->render('index.html.twig', array('films' => $films, 'typeFilm' => $typeFilm));
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
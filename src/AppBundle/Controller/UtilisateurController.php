<?php

namespace AppBundle\Controller;

use AppBundle\Form\LoginForm;
use AppBundle\Form\SigninForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Utilisateur;

class UtilisateurController extends Controller
{
    /**
     * @Route("/login", name="security_login")
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(LoginForm ::class, [
            '_username' => $lastUsername,
        ]);

        return $this->render(
            'security/login.html.twig',
            array(
                'form' => $form->createView(),
                'error' => $error,
            )
        );
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logOutAction()
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/register", name="user_register")
     */
    public function signInAction(Request $request)
    {
        $form = $this->createForm(SigninForm::class);
        $form->handleRequest($request);
        if($form->isValid())
        {
            /** @var Utilisateur $user */
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $user->setRoles(['ROLE_USER']);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Welcome'.$user->getEmailUtilisateur());
            return $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->get('app.security.login_form_authenticator'),
                    'main');

        }
        return $this->render(
            'security/signin.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }
}
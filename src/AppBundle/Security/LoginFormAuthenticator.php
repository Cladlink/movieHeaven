<?php

namespace AppBundle\Security;


use AppBundle\Form\LoginForm;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{

    // Ce sont les quatres services que nous utilisons pour la création d'un authentifieur

    //formFactory pour créer le formulaire d'authentification
    private $formFactory;

    //entityManager pour vérifier dans la bdd que les identifiants concordent
    private $entityManager;

    //router pour les redirections
    private $router;

    //passwordEncoder pour crypter les mots de passe
    private $passwordEncoder;

    public function __construct(FormFactoryInterface $formFactory,
                                EntityManager $entityManager,
                                RouterInterface $router,
                                UserPasswordEncoder $passwordEncoder)
    {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * getCredentials
     * @param Request $request
     *
     *      récupère les identifiants saisie
     *
     * @return mixed|null
     */
    public function getCredentials(Request $request)
    {
        $isLoginSubmit = $request->getPathInfo() == '/Utilisateur/login'&& $request->isMethod('POST');
        if (!$isLoginSubmit)
            return null;

        $form = $this->formFactory->create(LoginForm::class);
        $form->handleRequest($request);
        $data = $form->getData();

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $data['_username']
        );

        return $data;
    }

    /**
     * getUser
     *      recupere l'utilisateur dans la bdd
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return \AppBundle\Entity\Utilisateur|null|object
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['_username'];
        return $this->entityManager->getRepository('AppBundle:Utilisateur')
            ->findOneBy(['emailUtilisateur' => $username]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['_password'];
        if ($this->passwordEncoder->isPasswordValid($user, $password))
            return true;
        return false;
    }

    /**
     * getLoginUrl
     *      route empruntée pour se loguer (ou si le mdp est faux)
     *
     * @return string
     */
    protected function getLoginUrl()
    {
        return $this->router->generate('security_login');
    }

    /**
     * getDefaultSuccessRedirectUrl
     *      Route empruntée si l'authentification est correcte
     * @return string
     */
    protected function getDefaultSuccessRedirectUrl()
    {
        return $this->router->generate('home');
    }
}

<?php

namespace AppBundle\Doctrine;

use AppBundle\Entity\Utilisateur;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

/**
 * Class HashPasswordListener
 *      Classe détaillant le cheminement pour crypter les mots de passe. Cette étape n'était pas nécessaire
 *      mais est pédagogiquement interessante
 * Voici comment se passe le processus :
 * on créer l'entité Utilisateur. On définit un champ "password" qui sera utilisé pour stocker
 * le mot de passe crypté et un champ "plainPassword" qui lui servira de tampon entre le mot de passe
 * non crypté et le crypté. On interagit UNIQUEMENT avec plainPassword, lorsque l'on set le plainPassword
 * on met le password à nullainsi Doctrine trouvera une erreur ce qui déclanchera l'appel aux listeners
 * afin de générer une exception si aucun listener n'agit. Là en l'occurence nous avons définit un service qui
 * va prendre le plain password, crypter le mot de passe et mettre le mot de passe crypté dans password.
 * De même lorsque le mot de passe sera mis à jour, on refera le même schéma.
 * @package AppBundle\Doctrine
 */
class HashPasswordListener implements EventSubscriber
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * prepersist
     * @param LifecycleEventArgs $args
     *      Vérifie que le l'event capturé est bien de type utilisateur sinon on ne traite rien
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Utilisateur)
            return;
        $this->encodePassword($entity);
    }

    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }

    /**
     * preUpdate
     * @param LifecycleEventArgs $args
     *      vérifie que l'event capturé est bien de type Utilisateur et prépare le mot de passe à être décrypté
     *
     *
     */
    public function preUpdate(LifecycleEventArgs $args)
    {

        $entity = $args->getEntity();

        if (!$entity instanceof Utilisateur)
            return;

        $this->encodePassword($entity);
        // necessary to force the update to see the change
        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    /**
     * encodePassword
     *      met le mot de passé crypté dans le password (grace à Bcrypt définis dans les fichiers de config)
     * @param Utilisateur $entity
     */
    private function encodePassword(Utilisateur $entity)
    {
        if (!$entity->getPlainPassword()) {
            return;
        }
        $encoded = $this->passwordEncoder->encodePassword(
            $entity,
            $entity->getPlainPassword()
        );
        $entity->setPassword($encoded);
    }
}
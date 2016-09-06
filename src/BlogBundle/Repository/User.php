<?php
/**
 * Created by PhpStorm.
 * User: monika
 * Date: 18.06.16
 * Time: 21:36
 */

namespace BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User.
 * @package BlogBundle\Repository
 * @author Monika Malinowska
 */
class User extends EntityRepository
{
    /**
     * Save users object.
     * @param \BlogBundle\Entity\User $user
     */
    public function save(\BlogBundle\Entity\User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Delete answer object.
     *
     * @param User $user User object
     *
     * @return mixed
     */
    public function delete(\BlogBundle\Entity\User $user)
    {
        if (!$user) {
            throw $this->createNotFoundException('Nie odnaleziono użytkownika');
        }
        $this->_em->remove($user);
        $this->_em->flush();
    }
}

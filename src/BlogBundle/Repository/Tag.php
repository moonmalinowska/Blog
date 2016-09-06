<?php
/**
 * Created by PhpStorm.
 * User: monika
 * Date: 19.06.16
 * Time: 18:09
 */

namespace BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tag.
 * @package BlogBundle\Repository
 * @author Monika Malinowska
 */
class Tag extends EntityRepository
{

    /**
     * Save Tag object.
     *
     * @param Tag $tag Tag object
     */
    public function save(\BlogBundle\Entity\Tag $tag)
    {
        $this->_em->persist($tag);
        $this->_em->flush();
    }
    /**
     * Delete Tag object.
     *
     * @param Tag $tag Tag object
     */
    public function delete(\BlogBundle\Entity\Tag $tag)
    {
        $this->_em->remove($tag);
        $this->_em->flush();
    }
}

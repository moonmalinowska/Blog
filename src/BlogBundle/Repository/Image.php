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
 * Class Image.
 * @package BlogBundle\Repository
 * @author Monika Malinowska
 */
class Image extends EntityRepository
{
    /**
     * Return web path.
     *
     * @return mixed $webPath Web Path
     */
    public function getWebPath()
    {
        // ... $webPath being the full image URL, to be used in templates

        return $webPath;
    }


    /**
     * Save image object.
     *
     * @param Image $image Image object
     */
    public function save(\BlogBundle\Entity\Image $image)
    {
        $this->_em->persist($image);
        $this->_em->flush();
    }
    /**
     * Delete post object.
     *
     * @param Image $image Image object
     */
    public function delete(\BlogBundle\Entity\Image $image)
    {
        $this->_em->remove($image);
        $this->_em->flush();
    }
}

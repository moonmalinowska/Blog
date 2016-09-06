<?php
/**
 * Created by PhpStorm.
 * User: monika
 * Date: 06.05.16
 * Time: 18:37
 *
 * Enquiry repository.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class Enquiry.
 * @package BlogBundle\Repository
 * @author Monika Malinowska
 */
class Enquiry extends EntityRepository
{
    /**
     * Save enquiry object.
     *
     * @param Enquiry $enquiry Enquiry object
     */
    public function save(\BlogBundle\Entity\Enquiry $enquiry)
    {
        $this->_em->persist($enquiry);
        $this->_em->flush();
    }
}

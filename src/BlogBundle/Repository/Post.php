<?php
/**
 * Created by PhpStorm.
 * User: monika
 * Date: 16.05.16
 * Time: 19:36
 *
 * Post repository.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class Post.
 * @package BlogBundle\Repository
 * @author Monika Malinowska
 */
class Post extends EntityRepository
{
    /**
     * Save post object.
     *
     * @param \BlogBundle\Entity\Post|Post $post Post object
     */
    public function save(\BlogBundle\Entity\Post $post)
    {
        $this->_em->persist($post);
        $this->_em->flush();
    }

    /**
     * Delete post object.
     *
     * @param \BlogBundle\Entity\Post|Post $post Post object
     */
    public function delete(\BlogBundle\Entity\Post $post)
    {
        $this->_em->remove($post);
        $this->_em->flush();
    }
    /**
     * Method getLatestPosts returns the latest posts.
     * @param null $limit
     * @return array
     */
    public function getLatestPosts($limit = null)
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b')
            ->addOrderBy('b.created', 'DESC');

        if (false === is_null($limit)) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * Get all posts for page
     *
     * @return mixed
     */
    public function getAllPosts()
    {
        
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.created', 'DESC')
            ->getQuery();


        return $query;
    }
}

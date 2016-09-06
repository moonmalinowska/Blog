<?php
/**
 * Created by PhpStorm.
 * User: monika
 * Date: 26.05.16
 * Time: 17:14
 *
 * Comment repository.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class Comment.
 * @package BlogBundle\Repository
 * @author Monika Malinowska
 */
class Comment extends EntityRepository
{
    /**
     * Save comment object.
     *
     * @param \BlogBundle\Entity\Comment|Comment $comment Comment object
     */
    public function save(\BlogBundle\Entity\Comment $comment)
    {
        /**$comment->setPost($post);
        $qb = $this->createQueryBuilder('c')
            ->update('c')
            ->set('approved')
            ->where('c.id')
            ->setParameter('id', $comment->getId());
        return $qb->getQuery()
            ->getResult();*/
        //$comment->setApproved(1);
        $this->_em->persist($comment);
        $this->_em->flush();
        /*$comment->setCreated(new \DateTime());
        $em = $this->getEntityManager();
        $post = $em->getRepository('BlogBundle:Post')->findOneById($id);
        $comment->setPost($post);
        $this->_em->persist($comment);
        $this->_em->flush();*/
    }

    /**
     * Delete comment object.
     *
     * @param \BlogBundle\Entity\Comment|Comment $comment Comment object
     */
    public function delete(\BlogBundle\Entity\Comment $comment)
    {
        $this->_em->remove($comment);
        $this->_em->flush();
    }

    /**
     * Method getCommentsForPost returns comments for post.
     *
     * @param $post
     * @param $approved
     * @return array
     */
    public function getCommentsForPost($post, $approved = true)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.post = :post_id')
            ->addOrderBy('c.created')
            ->setParameter('post_id', $post->getId());

        if (false === is_null($approved)) {
            $qb->andWhere('c.approved = :approved')
                ->setParameter('approved', $approved);
        }

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * Get Not Approved
     * @return \Doctrine\ORM\Query
     */
    public function getNotApproved()
    {
        $query = $this->createQueryBuilder('c')
            ->where('c.approved' == '0')
            ->getQuery();


        return $query;
    }
}

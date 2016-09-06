<?php
/**
 * Created by PhpStorm.
 * User: monika
 * Date: 06.05.16
 * Time: 16:40
 *
 * User entity.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Enquiry.
 *
 * @package Model
 * @author Monika Malinowska
 *
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\User")
 * @ORM\Table(name="fos_users")
 */
class User extends BaseUser
{
    /**
     * Id
     *
     * @ORM\Id
     * @ORM\Column(type="integer",
     *     nullable=false,
     *     options={
     *         "unsigned" = true
     *     }
     * )
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer $id
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Post", mappedBy="user")
     *
     * @var $posts Posts
     */
    protected $posts;


    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->roles = array('ROLE_AUTHOR');
        $this->posts = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param string $id Id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
    /**
     * Add posts.
     *
     * @param \BlogBundle\Entity\Post $posts
     */
    public function addPost(\BlogBundle\Entity\Post $posts)
    {
        $this->posts[] = $posts;
    }

    /**
     * Remove posts.
     *
     * @param \BlogBundle\Entity\Post $posts
     */
    public function removePost(\BlogBundle\Entity\Post $posts)
    {
        $this->posts->removeElement($posts);
    }

    /**
     * Get post.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }
}

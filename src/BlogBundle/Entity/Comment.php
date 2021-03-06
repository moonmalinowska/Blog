<?php
/**
 * Created by PhpStorm.
 * User: monika
 * Date: 26.05.16
 * Time: 16:52
 *
 *  Comment entity.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Entity;

/*use BlogBundle\Repository\Post;*/
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Comment.
 *
 * @package Model
 * @author Monika Malinowska
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\Comment")
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\Column(
     *     type="integer",
     *     nullable=false,
     *     options={
     *         "unsigned" = true
     *     }
     * )
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $id Id
     */
    private $id;

    /**
     * @ORM\Column(
     *     name="author",
     *     type="string",
     *     length=128,
     *     nullable=false
     * )
     *
<<<<<<< HEAD
=======
     * @var string $author Author
     *
>>>>>>> 25989d1bcfd715b784a3bc03e2224d83b3f9893c
     * @Assert\NotBlank(groups={"comment-default"}, message = "Pole nie może pozostać puste.")
     * @Assert\Length(min=2, max=128, groups={"comment-default"})
     *
     * @var string $author Author
     */
    protected $author;

    /**
     * @ORM\Column(
     *     name="content",
     *     type="text",
     *     nullable=false
     * )
     *
     * @var string $content Content
     *
     * @Assert\NotBlank(groups={"comment-default"}, message = "Pole nie może pozostać puste.")
     */
    protected $content;

    /**
     * @ORM\Column(
     *     name="created",
     *     type="datetime",
     *     nullable=false
     * )
     * @var \DateTime $created Created
     */
    protected $created;

    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var array $post Post
     */
    protected $post;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean $approved Approved
     */
    protected $approved;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created = new \DateTime();
        $this->approved = false;

        //$this->posts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set approved.
     *
     * @param boolean $approved
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    }

    /**
     * Get approved.
     *
     * @return boolean
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set Id.
     *
     * @param integer $id Id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get Id.
     *
     * @return integer Result
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content.
     *
     * @param string $content Content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content.
     *
     * @return string Content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set author.
     *
     * @param string $author Author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Get content.
     *
     * @return string Content
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Get created.
     *
     * @return \DateTime Created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set created.
     *
     * @param \DateTime $created Created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Set Post.
     *
     * @param integer $post Post
     */
    public function setPost(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get Post.
     *
     * @return integer Result
     */
    public function getPost()
    {
        return $this->post;
    }
}

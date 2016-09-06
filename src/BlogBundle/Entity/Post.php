<?php
/**
 * Created by PhpStorm.
 * User: monika
 * Date: 12.05.16
 * Time: 21:45
 *
 * Post entity.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Entity;

use BlogBundle\Repository\Image;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class Post.
 *
 * @package Model
 * @author Monika Malinowska
 *
 * @ORM\Table(name="posts")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\Post")
 * @Vich\Uploadable
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields="id", groups={"post-default"})
 */
class Post
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
     *     name="title",
     *     type="string",
     *     length=128,
     *     nullable=false
     * )
     *
     * @Assert\NotBlank(groups={"post-default"}, message = "Pole nie może zostać puste.")
     * @var string $title title
     */
    protected $title;

    /**
     * @ORM\Column(
     *     name="content",
     *     type="text",
     *     nullable=false
     * )
     *
     * @Assert\NotBlank(groups={"post-default"}, message = "Pole nie może zostać puste.")
     * @var $content Content
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
     * @ORM\Column(
     *     name="updated",
     *     type="datetime",
     *     nullable=false
     * )
     * @var \DateTime $updated Updated
     */
    protected $updated;

    /**
     * Tags array
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="posts", orphanRemoval=true)
     * @ORM\JoinTable(
     *      name="posts_tags"
     * )
     *
     * @Assert\NotBlank(groups={"post-default"}, message = "Pole nie może zostać puste.")
     *
     * @var \Doctrine\Common\Collections\ArrayCollection $tags
     */
    protected $tags;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post")
     *
     * @var $comments Comment
     */
    protected $comments;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="posts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @var $user User
     */
    protected $user;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var $imageName Image
     */
    protected $imageName;

    /**
     * @Assert\Image(
     *     maxSize="100000",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     *
     * @Vich\UploadableField(mapping="upload_image", fileNameProperty="imageName", nullable=true)
     *
     * @var File $imageFile ImageFile
     */
    protected $imageFile;
    

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->created= new \DateTime();
        $this->updated= new \DateTime();
    }


    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return $this $image
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated = new \DateTime('now');
        }

        return $this;
    }

    /**
     * Get Image File
     *
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set Image File
     *
     * @param string $imageName
     * @return $this $imageName
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get Image Name
     *
     * @return $this
     */
    public function getImageName()
    {
        return $this->imageName;
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
     * Set title.
     *
     * @param string $title Title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get user.
     *
     * @return string User
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Set user.
     *
     * @param string $user User
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get title.
     *
     * @return string Title
     */
    public function getTitle()
    {
        return $this->title;
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
     * @param null $length
     * @return string Content
     */
    public function getContent($length = null)
    {
        if (false === is_null($length) && $length > 0) {
            return substr($this->content, 0, $length);
        } else {
            return $this->content;
        }
    }

    /**
     * Get created.
     *
     * @return integer Created
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set created.
     *
     * @param string $created Created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Get updated.
     *
     * @return string Updated
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set updated.
     *
     * @param string $updated Updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * Add tags.
     *
     * @param \BlogBundle\Entity\Tag $tags
     */
    public function addTag(\BlogBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;
    }

    /**
     * Remove tags
     *
     * @param \BlogBundle\Entity\Tag $tags
     */
    public function removeTag(\BlogBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }



    /**
     * Add comments.
     *
     * @param \BlogBundle\Entity\Comment $comments
     */
    public function addComment(\BlogBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;
    }

    /**
     * Remove comments
     *
     * @param \BlogBundle\Entity\Comment $comments
     */
    public function removeComment(\BlogBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }
}

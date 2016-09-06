<?php
/**
 * Tag entity.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Tag.
 *
 * @package Model
 * @author Monika Malinowska
 *
 * @ORM\Table(name="tags")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\Tag")
 * @UniqueEntity(fields="name", groups={"tag-default"})
 */
class Tag
{
    /**
     * Id.
     *
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
     * @var integer $id
     */
    private $id;

    /**
     * Name.
     *
     * @ORM\Column(
     *     name="name",
     *     type="string",
     *     length=128,
     *     nullable=false
     * )
     *
     * @Assert\NotBlank(groups={"tag-default"}, message = "Pole nie może zostać puste.")
     * @Assert\Length(min=3, max=128, groups={"tag-default"})
     * @var string $name
     */
    private $name;
    /**
     * Posts.
     *
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="tags", cascade={"all"}, orphanRemoval=true)
     *
     *
     * @var \Doctrine\Common\Collections\ArrayCollection $posts
     */
    private $posts;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * Get Posts.
     *
     * @return integer Result
     */
    public function getPosts()
    {
        return $this->posts;
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
     * Set id.
     *
     * @param string $id Id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set name.
     *
     * @param string $name Name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name.
     *
     * @return string Name
     */
    public function getName()
    {
        return $this->name;
    }
}

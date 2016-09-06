<?php
/**
 * Created by PhpStorm.
 * User: monika
 * Date: 06.05.16
 * Time: 16:40
 *
 * Enquiry entity.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */


namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Enquiry.
 *
 * @package Model
 * @author Monika Malinowska
 *
 * @ORM\Table(name="enquiries")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\Enquiry")
 */
class Enquiry
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
     *     name="name",
     *     type="string",
     *     length=64,
     *     nullable=false
     * )
     *
     * @var string $name Name
     */
    protected $name;

    /**
     * @ORM\Column(
     *     name="email",
     *     type="string",
     *     length=128,
     *     nullable=false
     * )
     *
     * @var string $email Email
     */
    protected $email;
    /**
     * @ORM\Column(
     *     name="subject",
     *     type="string",
     *     length=128,
     *     nullable=false
     * )
     *
     * @var string $subject Subject
     */
    protected $subject;

    /**
     * @ORM\Column(
     *     name="content",
     *     type="text",
     *     nullable=false
     * )
     *
     * @var string $content Content
     */
    protected $content;

    /**
     * Get name.
     *
     * @return string Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Id.
     *
     * @param string $name Name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get email.
     *
     * @return string Email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set Id.
     *
     * @param string $email Email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get subject.
     *
     * @return string Subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set Id.
     *
     * @param string $subject Subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
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
     * Set Id.
     *
     * @param string $content Content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get id.
     *
     * @return string Id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Id.
     *
     * @param string $id Id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}

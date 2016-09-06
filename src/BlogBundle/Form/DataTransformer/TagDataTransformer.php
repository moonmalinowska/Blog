<?php
/**
 * Tag data transformer.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Form\DataTransformer;

use BlogBundle\Entity\Tag;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class TagDataTransformer.
 *
 * @package BlogBundle\Form\DataTransformer
 * @author Monika Malinowska
 */
class TagDataTransformer implements DataTransformerInterface
{
    /**
     * Model object.
     *
     * @var ObjectRepository $model
     */
    private $model = null;

    /**
     * TagDataTransformer constructor.
     *
     * @param ObjectRepository $model
     */
    public function __construct(ObjectRepository $model)
    {
        $this->model = $model;
    }

    /**
     * Transform.
     *
     * @param array $tags Array of tag objects
     *
     * @return string Result
     */
    public function transform($tags)
    {
        if (!$tags) {
            return '';
        }

        $result = array();

        foreach ($tags as $tag) {
            $result[] = $tag->getName();
        }

        return join(', ', $result);

    }

    /**
     * Reversed transform.
     *
     * @param string $tags Tag names
     *
     * @return array Result
     */
    public function reverseTransform($tags)
    {
        if (!$tags) {
            return array();
        }

        $result = array();
        $tagsNames = explode(',', $tags);

        foreach ($tagsNames as $name) {
            $name = trim($name);

            $tag = $this->model->findOneByName($name);

            if (!$tag) {
                $tag = new Tag();
                $tag->setName($name);
                $this->model->save($tag);
            }

            $result[] = $tag;
        }

        return $result;
    }
}

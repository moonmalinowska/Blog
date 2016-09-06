<?php
/**
 * Image data transformer.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Form\DataTransformer;

use BlogBundle\Entity\Image;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class ImageDataTransformer.
 *
 * @package BlogBundle\Form\DataTransformer
 * @author Monika Malinowska
 */
class ImageDataTransformer implements DataTransformerInterface
{
    /**
     * Model object.
     *
     * @var ObjectRepository $model
     */
    private $model = null;

    /**
     * ImageDataTransformer constructor.
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
     * @param array $image Array of image objects
     *
     * @return string Result
     */
    public function transform($image)
    {
        if (null === $image) {
            return '';
        }

       /* $result = '';

        foreach ($images as $image) {
            $result[] = $image->getName();
        }

        return join(', ', $result);*/
        return $image->getId();

    }

    /**
     * Reversed transform.
     *
     * @param string $images Image names
     *
     * @return array Result
     */
    public function reverseTransform($images)
    {
        
        if (null === $images) {
            return array();
        }

        $result = array();
        $imagesNames = explode(',', $images);

        foreach ($imagesNames as $imageName) {
            $imageName = trim($imageName);

            $image = $this->model->find($imageName);

            if (!$image) {
                $image = new Image();
                $image->setImageName($imageName);
                $this->model->save($image);
            }

            $result[] = $image;
        }

        return $result;
    }
}

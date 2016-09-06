<?php
/** Image Type.
 * Created by PhpStorm.
 * User: monika
 * Date: 19.06.16
 * Time: 17:32
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormTypeExtensionInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Class ImageType.
 *
 * @package BlogBundle\Form
 * @author Monika Malinowska
 */
class ImageType extends AbstractType
{

    /**
     * Form builder.
     *
     * @param FormBuilderInterface $builder Form builder
     * @param array $options Form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'id',
            'hidden'
        );
        if (isset($options['validation_groups'])
            && count($options['validation_groups'])
            && !in_array('image-delete', $options['validation_groups'])
        ) {
           // $builder->add('name', 'file', array('label' => 'Obrazek (format JPG)'));
            //$builder->add('name', 'text');
            $builder->add('imageFile', 'vich_image');
        }
        $builder->add(
            'save',
            'submit',
            array(
                'label' => 'Save'
            )
        );
    }

    /**
     * Sets default options for form.
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'BlogBundle\Entity\Image',
                'validation_groups' => 'image-default',
            )
        );
    }

    /**
     * Getter for form name.
     *
     * @return string Form name
     */
    public function getName()
    {
        return 'image_form';
    }
}

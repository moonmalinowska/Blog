<?php
/**
 * Post type.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Form;

use BlogBundle\Form\DataTransformer\TagDataTransformer;
use BlogBundle\Form\DataTransformer\ImageDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\File\File;
use BlogBundle\Form\ImageType;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Class PostType.
 *
 * @package BlogBundle\Form
 * @author Monika Malinowska
 */
class PostType extends AbstractType
{
    /**
     * Form builder.
     *
     * @param FormBuilderInterface $builder Form builder
     * @param array $options Form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $tagDataTransformer = new TagDataTransformer($options['tag_model']);

        $builder->add(
            'id',
            'hidden'
        );
        if (isset($options['validation_groups'])
            && count($options['validation_groups'])
            && !in_array('post-delete', $options['validation_groups'])
        ) {
            $builder->add(
                'title',
                'text',
                array(
                    'label'      => 'Tytuł posta',
                    'required'   => true,
                    'max_length' => 255,
                )
            );
            $builder->add(
                'content',
                'textarea',
                array(
                    'label'    => 'form.post.content',
                    'required' => true,
                )
            );
            $builder->add(
                $builder
                    ->create('tags', 'text')
                    ->addModelTransformer($tagDataTransformer)
            );
            $builder->add(
                'imageFile',
                'file',
                array(
                    'required'   => true
                )
            );
        }
        $builder->add(
            'save',
            'submit',
            array(
                'label' => 'Zatwierdź'
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
                'data_class' => 'BlogBundle\Entity\Post',
                'validation_groups' => 'post-default',
            )
        );
        $resolver->setRequired(array('post_model'));
        $resolver->setAllowedTypes(
            array(
                'post_model' => 'Doctrine\Common\Persistence\ObjectRepository'
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
        return 'post_form';
    }
}

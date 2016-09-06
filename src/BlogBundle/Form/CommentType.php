<?php
/**
 * Created by PhpStorm.
 * User: monika
 * Date: 26.05.16
 * Time: 20:10
 *
 *  Comment type.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CommentType.
 *
 * @package BlogBundle\Form
 * @author Monika Malinowska
 */
class CommentType extends AbstractType
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
            && !in_array('comment-delete', $options['validation_groups'])
            && !in_array('comment-edit', $options['validation_groups'])
            && !in_array('comments-list', $options['validation_groups'])

        ) {
            $builder->add(
                'author',
                'text',
                array(
                    'label' => 'form.comment.author',
                    'required' => true,
                    'max_length' => 128,
                )
            );
            $builder->add(
                'content',
                'textarea',
                array(
                    'label' => 'form.comment.content',
                    'required' => true,
                )
            );
        }
        if (isset($options['validation_groups'])
            && count($options['validation_groups'])
            && in_array('comment-edit', $options['validation_groups'])
        ) {
            /** $builder->add(
             * 'approved',
             * 'choice',
             * array(
             * 'expanded' => 'true',
             * 'choices' => array(
             * 1 => 'Zaakceptuj',
             * ),
             * 'label' => 'Zaakceptuj',
             * )
             * );*/
            $builder->add(
                'approved',
                'submit',
                array(
                    'label' => 'Zaakceptuj'
                )
            );
            /*$builder->add(
                'save',
                'submit',
                array(
                    'label' => 'Zatwierdź'
                )
            );*/
            /**$builder->add(
             * 'approved',
             * 'submit',
             * array(
             * 'label' => 'Zatwierdź',
             * )
             * );*/
        }
        if (isset($options['validation_groups'])
            && count($options['validation_groups'])
            && !in_array('comment-edit', $options['validation_groups'])

        ) {
            $builder->add(
                'save',
                'submit',
                array(
                    'label' => 'Zatwierdź'
                )
            );
        }
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
                'data_class' => 'BlogBundle\Entity\Comment',
                'validation_groups' => 'comment-default',
            )
        );
    }

    /**
     * Get name method.
     *
     * @return string
     */
    public function getName()
    {
        return 'comment_form';
    }
}

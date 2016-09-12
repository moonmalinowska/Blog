<?php
/**
 * User type.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class UserType.
 *
 * @package BlogBundle\Form
 * @author Monika Malinowska
 */
class UserType extends AbstractType
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
            'hidden',
            array('mapped' => false)
        );
        if (isset($options['validation_groups'])
            && count($options['validation_groups'])
            && !in_array('user-delete', $options['validation_groups'])
        ) {
            $builder->add(
                'username',
                'text',
                array(
                    'label' => 'Nazwa użytkownika',
                    'required' => true,
                    'max_length' => 128,
                )
            );
            if ($options['edit'] === false) {
                $builder->add(
                    'plainPassword',
                    'password',
                    array(
                        'label' => 'Nowe hasło',
                        'max_length' => 128,
                        'required' => true
                    )
                );
            } else {
                $builder->add(
                    'plainPassword',
                    'password',
                    array(
                        'label' => 'Nowe hasło',
                        'max_length' => 128,
                        'required' => false
                    )
                );
            }
            $builder->add(
                'email',
                'text',
                array(
                    'label' => 'Email',
                    'required' => true,
                    'max_length' => 128
                )
            );
            $builder->add(
                'enabled',
                'checkbox',
                array(
                    'label' => 'Aktywny',
                    'max_length' => 128,
                    'required' => false,
                    'value' => 1
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
                'data_class' => 'BlogBundle\Entity\User',
                'edit' => false
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
        return 'user_form';
    }
}

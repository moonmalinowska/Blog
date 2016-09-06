<?php
/**
 * ChangeRole type.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ChangeRoleType.
 *
 * @package BlogBundle\Form
 * @author Monika Malinowska
 */
class ChangeRoleType extends AbstractType
{

    /**
     * Form builder.
     *
     * @param FormBuilderInterface $builder Form builder
     * @param array $options Form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = array(
            'ROLE_AUTHOR'        => 'Użytkownik',
            'ROLE_MOD'      =>    'Moderator',
            'ROLE__SUPER_ADMIN'     => 'Admin'
        );

        $builder->add(
            'role',
            'choice',
            array(
                'label'   => 'Role',
                'choices' => $roles,
                'attr'    => array(
                    'class' => "form-control"
                )
            )
        );

        $builder->add(
            'Zmień rolę',
            'submit',
            array(
                'label' => 'Zapisz',
                'attr' => array('class' => 'btn btn-success btn-save')
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
            array()
        );
    }

    /**
     * Getter for form name.
     *
     * @return string Form name
     */
    public function getName()
    {
        return 'change_role_form';
    }
}

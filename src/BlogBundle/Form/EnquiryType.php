<?php
/**
 * Created by PhpStorm.
 * User: monika
 * Date: 06.05.16
 * Time: 17:01
 *
 * Enquiry type.
 *
 * @copyright (c) 2016 Monika Malinowska
 * @link http://wierzba.wzks.uj.edu.pl/~12_malinowska/blog
 */

namespace BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class EnquiryType.
 *
 * @package BlogBundle\Form
 * @author Monika Malinowska
 */
class EnquiryType extends AbstractType
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
        $builder->add(
            'name',
            'text'
        );
        $builder->add(
            'email',
            'email'
        );
        $builder->add(
            'subject',
            'text'
        );
        $builder->add(
            'content',
            'textarea'
        );
        
    }

    /**
     * Getter for form name.
     *
     * @return string Form name
     */
    public function getName()
    {
        return 'contact';
    }
}

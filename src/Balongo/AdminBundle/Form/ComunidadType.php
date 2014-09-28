<?php

namespace Balongo\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ComunidadType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text', array('attr'=>array('class'=>'form-control')))
            ->add('direccion', 'textarea', array('label' => 'Dirección', 'attr'=>array('class'=>'form-control')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Balongo\AdminBundle\Entity\Comunidad'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'balongo_adminbundle_comunidad';
    }
}

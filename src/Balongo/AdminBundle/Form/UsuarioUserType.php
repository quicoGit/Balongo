<?php

namespace Balongo\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioUserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'repeated', array(
            	'type' => 'email',
            	'invalid_message' => 'Los emails deben coincidir.',
            	'options' => array('attr'=>array('class'=>'form-control')),
            	'required' => true,
					'first_options'  => array('label' => 'Email'),
					'second_options' => array('label' => 'Repite Email')
            ))
            ->add('nombre', 'text', array('attr'=>array('class'=>'form-control')))
            ->add('apellidos', 'text', array('attr'=>array('class'=>'form-control')))
            ->add('comunidades', 'entity', array(
            	'class' => 'AdminBundle:Comunidad',
            	'multiple' => true,
            	'attr' => array('class' => 'form-control')
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Balongo\AdminBundle\Entity\Usuario'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'balongo_adminbundle_usuario_user';
    }
}

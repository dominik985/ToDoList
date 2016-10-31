<?php

namespace ToDoListBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('label' => 'Tytuł:','attr'=>array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('description', 'textarea', array('label' => 'Opis:','attr'=>array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('date', 'datetime', array('label' => 'Data Wykonania:','attr'=>array('style' => 'margin-bottom:15px')))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ToDoListBundle\Entity\Task'
        ));
    }
}

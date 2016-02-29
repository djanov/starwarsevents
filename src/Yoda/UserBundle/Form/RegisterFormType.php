<?php

namespace Yoda\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class RegisterFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
      $builder->add('username', 'text')
              ->add('email', 'email', array (
                  //  'required' => false,
                    'label' => 'Email Address',
                    'attr' => array('class' => 'C-3PO')
              ))
              ->add('plainPassword', 'repeated', array(
                    'type' => 'password'
              ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
      $resolver->setDefaults(array (
        'data_class' => 'Yoda\UserBundle\Entity\User',
      ));
    }

    public function finishView(FormView $view, FormInterface $form, array $options) {
      $view['email']->vars['help'] = 'Hint: it will have an @ symbol';
    }

    public function getName() {
      return 'user_register';
    }
}

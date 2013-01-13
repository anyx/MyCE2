<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Anyx\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array(
                'label' => 'Логин'
            ))
            ->add('name', 'text', array(
                'label' => 'Имя'
            ))
            ->add('email', 'email')
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => $this->class,
            'intention'  => 'profile',
        );
    }    
    
    public function getName()
    {
        return 'anyx_user_profile';
    }
}

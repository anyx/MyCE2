<?php

namespace Anyx\CrosswordBundle\Form\Extension\Type;

//namespace Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class TagsType extends AbstractType
{
    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'tags';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'field';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            // hidden fields cannot have a required attribute
            'required'       => false,
            // Pass errors to the parent
            'error_bubbling' => true,
            'compound'       => false,
        ));
    }
}
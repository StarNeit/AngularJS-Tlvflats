<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 25/12/15
 * Time: 15:05
 */

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyImageType extends AbstractType
{

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "app_property_image";
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('url', new ImageType());
        $builder->add('description', 'text', array("required" => false));
        $builder->add('remove', 'checkbox', array("required" => false));
        $builder->add('regenerate_thumbnail', 'checkbox', array("required" => false));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PropertyImage',
        ));
    }
}
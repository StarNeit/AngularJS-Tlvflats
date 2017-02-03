<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 10/07/16
 * Time: 12:49
 */

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComponentType extends AbstractType
{

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "app_component";
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array('required' => false));
        $builder->add('size', 'integer');
        $builder->add('amenities', 'collection',
            array(
                "required" => false,
                "type" => 'text',
                'allow_add' => true,
                'allow_delete' => true,
                'options' => array(
                    "attr" => array("class" => "hint_amenity"),
                )
            ));
        $builder->add('add_amenity', 'button', array(
            'attr' => array('class' => 'add_amenity'),
            'label' => "Add amenity"
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\RoomComponent',
        ));
    }


}
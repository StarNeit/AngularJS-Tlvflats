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

class ChannelPropertyType extends AbstractType
{

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "app_channel_property";
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('property_id', 'hidden');
        $builder->add('title', 'hidden');
        $builder->add('address', 'hidden');
        $builder->add('channel_id', 'text', array("required" => false, "attr"=>array("class"=>"hint_channel")));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ChannelPropertyDTO',
        ));
    }
}
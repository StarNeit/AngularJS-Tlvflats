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

class RateScopeType extends AbstractType
{

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "app_rate_scope";
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('displayName', 'text', array("required" => true));
        $builder->add('minDays', 'number', array("required" => true));
        //$builder->add('id', 'hidden');
        $builder->add('remove', 'submit');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\RateScope',
        ));
    }
}
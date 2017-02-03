<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 25/12/15
 * Time: 13:02
 */

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;

class ImageType extends AbstractType
{

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'app_image';
    }

    public function getParent()
    {
        return 'hidden';
    }


}
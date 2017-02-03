<?php
/**
 * Created by IntelliJ IDEA.
 * User: tinedel
 * Date: 23/05/16
 * Time: 11:07
 */

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\Channel;
use AppBundle\Entity\ChannelOption;
use AppBundle\Entity\ChannelPropertiesDTO;
use AppBundle\Entity\ChannelProperty;
use AppBundle\Entity\ChannelPropertyDTO;
use AppBundle\Entity\OptionsTemplate;
use AppBundle\Entity\Property;
use AppBundle\Form\Type\ChannelPropertyType;
use AppBundle\Form\Type\OptionType;
use AppBundle\Service\ChannelServiceInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ChannelManagerController extends Controller
{
    /**
     * @Route("/admin/channel_manager/{channelId}", name="manage_channel")
     */
    public function manageChannelAction(Request $request, $channelId, $propertyId = null)
    {

        $channel = $this->getDoctrine()->getRepository("AppBundle:Channel")->find($channelId);
        $savedChannelProperties = $this->getDoctrine()->getRepository("AppBundle:ChannelProperty")->findBy(
            array("channel" => $channel)
        );

        /** @var ChannelServiceInterface $service */
        $service = $this->get($channel->getServiceName());

        $options = $service->getOptionsTemplate($channel);
        $channelProperties = $service->getProperties($channel);
        if (isset($propertyId))
            $properties = array($this->getDoctrine()->getRepository("AppBundle:Property")->find($propertyId));
        else
            $properties = $this->getDoctrine()->getRepository("AppBundle:Property")->findAll();

        $channelPropertiesDTO = $this->initDTO($properties, $savedChannelProperties);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var FormFactory $ff */
        $ff = $this->get('form.factory');

        $optionsArray = $this->prepareArray($channel, $options);

        $formOptions = $this->appendFields($ff->createNamedBuilder('formOptions', 'form', $optionsArray), $options)
            ->add('save', 'submit', array('label' => 'Save options'))
            ->getForm();

        $formChannelProperties = $ff->createNamedBuilder('formChannelProperties', 'form', $channelPropertiesDTO)
            ->add("channelProperties", "collection", array("type" => new ChannelPropertyType()))
            ->add("save", "submit", array("label" => "Save mappings"))
            ->add("saveTop", "submit", array("label" => "Save mappings"))
            ->getForm();

        $formOptions->handleRequest($request);
        $formChannelProperties->handleRequest($request);

        if ($formOptions->isValid()) {
            $updateds = $this->updateOptions($channel, $formOptions->getData(), $options);
            foreach ($updateds as $updated)
                $em->persist($updated);

            $em->flush();
            return $this->redirectToRoute("manage_channel", array(
                "channelId" => $channelId,
                "propertyId" => $propertyId
            ));
        } elseif ($formChannelProperties->isValid()) {
            $this->updateChannelProperties($em, $channel, $channelPropertiesDTO, $channelProperties, $properties, $savedChannelProperties);

            return $this->redirectToRoute("manage_channel", array(
                "channelId" => $channelId,
                "propertyId" => $propertyId
            ));
        }

        return $this->render('default/admin/manageChannel.html.twig', array(
            'formOptions' => $formOptions->createView(),
            'formChannelProperties' => $formChannelProperties->createView(),
            'channel' => $channel,
            'properties' => $properties,
            'channelProperties' => $channelProperties
        ));
    }

    private function prepareArray(Channel $channel, OptionsTemplate $options)
    {
        $res = array();
        /** @var ChannelOption $option */
        foreach ($options->getOptions() as $option) {
            $setoption = $channel->getOption($option->getName());
            if (isset($setoption))
                $res[$option->getName()] = $setoption->getVal();
            else
                $res[$option->getName()] = $option->getVal();
        }
        return $res;
    }

    /** @return FormBuilderInterface */
    private function appendFields(FormBuilderInterface $builder, OptionsTemplate $options)
    {
        /** @var ChannelOption $option */
        foreach ($options->getOptions() as $option) {
            $builder->add($option->getName(), $option->getType(), array("required" => false));
        }
        return $builder;
    }

    private function updateOptions(Channel $channel, $optionsArray, OptionsTemplate $options)
    {
        $res = array();
        foreach ($optionsArray as $k => $v) {
            $chOpt = $channel->getOption($k);
            if (!isset($chOpt))
                $chOpt = $options->getOption($k);
            if (!isset($chOpt)) {
                continue;
            }

            $chOpt->setVal($v);
            $res[] = $chOpt;
        }

        return $res;
    }

    /** @return ChannelPropertiesDTO */
    private function initDTO($properties, $savedChannelProperties)
    {
        $res = array();
        foreach ($properties as $property) {
            $channelProperty = $this->findChannelProperty($property, $savedChannelProperties);

            $res[] = (new ChannelPropertyDTO())->init($property, $channelProperty);
        }

        return (new ChannelPropertiesDTO())->setChannelProperties($res);
    }

    private function findChannelProperty(Property $property, $channelProperties)
    {
        /** @var ChannelProperty $channelProperty */
        foreach ($channelProperties as $channelProperty) {

            if (!empty($channelProperty->getProperty()) &&
                $property->getId() === $channelProperty->getProperty()->getId()
            ) {
                return $channelProperty;
            }
        }

        return null;
    }

    private function updateChannelProperties(EntityManager $em, Channel $channel, ChannelPropertiesDTO $channelPropertiesDTO, $channelProperties, $properties, $savedChannelProperties)
    {
        $this->get("logger")->error("Dtos: ".count($channelPropertiesDTO->getChannelProperties()));
        $this->get("logger")->error(json_encode($channelPropertiesDTO));
        /** @var ChannelPropertyDTO $channelPropertyDTO */
        foreach ($channelPropertiesDTO->getChannelProperties() as $channelPropertyDTO) {
            if (!empty($channelPropertyDTO->getChannelId())) {
                $savedChannelProperty = $this->findChannelPropertyByChannelId($channelPropertyDTO->getChannelId(), $savedChannelProperties);
                if (!isset($savedChannelProperty) || $savedChannelProperty->getProperty()->getId() != $channelPropertyDTO->getPropertyId()) {

                    $channelProperty = $this->findChannelPropertyByChannelId($channelPropertyDTO->getChannelId(), $channelProperties);

                    if (isset($channelProperty)) {
                        $property = $this->findProperty($channelPropertyDTO->getPropertyId(), $properties);
                        if (isset($property)) {
                            $channelProperty->setProperty($property);

                            $em->persist($channelProperty);
                        }
                    }
                }
            }
        }

        /** @var ChannelProperty $savedChannelProperty */
        foreach ($savedChannelProperties as $savedChannelProperty) {
            $found = false;
            foreach ($channelPropertiesDTO->getChannelProperties() as $channelPropertyDTO) {
                if($channelPropertyDTO->getChannelId() == $savedChannelProperty->getChannelId()
                && $channelPropertyDTO->getPropertyId() == $savedChannelProperty->getProperty()->getId()) {
                    $found = true;
                    break;
                }
            }
            if(!$found) {
                $em->remove($savedChannelProperty);
            }
        }

        $em->flush();
    }

    private function findChannelPropertyByChannelId($channelId, $channelProperties)
    {
        /** @var ChannelProperty $channelProperty */
        foreach ($channelProperties as $channelProperty) {
            if ($channelProperty->getChannelId() == $channelId) {
                return $channelProperty;
            }
        }

        return null;
    }

    private function findProperty($propertyId, $properties)
    {
        /** @var Property $property */
        foreach ($properties as $property) {
            if ($property->getId() == $propertyId) {
                return $property;
            }
        }

        return null;
    }
}
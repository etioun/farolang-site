<?php
namespace Farola\ProfileBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class Capitalizer implements DataTransformerInterface
{
    /**
     * business to form
     */
    public function transform($string)
    {
        return ucfirst($string);
    }

    /**
     * form to business
     */
    public function reverseTransform($string)
    {
        return ucfirst($string);
    }
}
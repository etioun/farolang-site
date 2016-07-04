<?php
namespace Farola\ProfileBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class UrlTransformer implements DataTransformerInterface
{   
    /**
     * business to form
     */
    public function transform($url)
    {
        return $url;
    }

    /**
     * form to business
     */
    public function reverseTransform($url)
    {
        if ($url == null || empty($url))
            return $url;

        $result = explode('https://', $url, 2);
        if (sizeof($result) > 1)
            return $result[1];

        $result = explode('http://', $url, 2);

        if (sizeof($result) > 1)
            return $result[1];


        return $url;
    }
}
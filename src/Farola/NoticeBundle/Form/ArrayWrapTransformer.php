<?php
namespace Farola\NoticeBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class ArrayWrapTransformer implements DataTransformerInterface
{
    protected $endChar;

    public function __construct($endChar = '') {
        $this->endChar = $endChar;
    }

    /**
     * business to form
     */
    public function transform($array)
    {
        if (count($array) > 0)
        {
            if (strlen($this->endChar) > 0) {
                return substr($array[0],0, strlen($array[0]) -1);
            }
            return $array[0];
        }
        return null;
    }

    /**
     * form to business
     */
    public function reverseTransform($elt)
    {
        if (empty($elt))
            return null;

        return array($elt.$this->endChar);
    }
}
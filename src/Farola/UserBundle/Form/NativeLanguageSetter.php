<?php
namespace Farola\UserBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class NativeLanguageSetter implements DataTransformerInterface
{
    /**
     * business to form
     */
    public function transform($languageLvlArr)
    {
        foreach ($languageLvlArr as $languageLvl) {
            List($language, $level) = explode(':',$languageLvl);

            if($level == 6)
            {
                return $language;
            }
        }
        return null;
    }

    /**
     * form to business
     */
    public function reverseTransform($languageCode)
    {
        return array($languageCode.':6');
    }
}
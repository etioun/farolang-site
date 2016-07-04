<?php
namespace Farola\ProfileBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class SpokenLanguagesCollTransformer implements DataTransformerInterface
{   
    /**
     * business to form
     */
    public function transform($spokenLanguages)
    {
        $result=[];
        
        foreach ($spokenLanguages as $spokenLanguageStr) {
            $split = preg_split('/:/', $spokenLanguageStr, 2);

            if (sizeof($split) != 2)
               continue;

            $result[] =  array('language' => $split[0], 'level' => $split[1]);    
        }

        return $result;
        
    }

    /**
     * form to business
     */
    public function reverseTransform($spokenLanguages)
    {
        $levels = array();

        foreach ($spokenLanguages as $spokenLanguage) {
            $lvl = intval($spokenLanguage['level']);
            
            if (isset($levels[$lvl]) == false)
                $levels[$lvl] = [];

            $levels[$lvl][] = $spokenLanguage['language'].':'.$spokenLanguage['level'];
        }

        if(empty($levels))
            return $levels;
        
        krsort($levels);
        return call_user_func_array ( 'array_merge', $levels );
    }
}
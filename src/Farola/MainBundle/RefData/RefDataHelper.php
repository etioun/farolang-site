<?php

namespace Farola\MainBundle\RefData;

use Symfony\Component\Intl\Intl;

class RefDataHelper
{
    private $filteredLanguages;

    public function getLanguages() {
        if (empty($this->filteredLanguages))
        {
            $this->filteredLanguages=[];
            foreach (Intl::getLanguageBundle()->getLanguageNames() as $key => $value) {
                if (strlen($key) > 3 ){
                     continue;
                }
                $this->filteredLanguages['-'.$key.'-']=$value;
            }
            $extLanguages = json_decode(file_get_contents(__DIR__.'/../Resources/ref_data/extension_languages/en.json'), true);
            $this->filteredLanguages = array_merge($extLanguages, $this->filteredLanguages);
        }
        return $this->filteredLanguages;
    }

    public function getLanguageName($code)
    {
        if (is_array($code))
        {
            $code = $code[0];
        }

        $languages = $this->getLanguages();
        
        if (array_key_exists($code,$languages))
        {
            return $languages[$code];   
        }
        return "Unknown";
        
        // $code = substr($code, 1, strlen($code)-2);

        // return Intl::getLanguageBundle()->getLanguageName($code, null,'en');
    }
}

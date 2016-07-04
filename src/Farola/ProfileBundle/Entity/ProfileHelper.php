<?php

namespace Farola\ProfileBundle\Entity;
 
use Farola\UserBundle\Entity\User;
use Symfony\Component\Intl\Intl;


class ProfileHelper
{
	private $entityManager;

    private $kernelService;

    private $securityContext;

    private $profilePictureDirPath;

    private $profilePictureDirUrl;

    private $refDataHelper;

    public function __construct(
        $entityManager, 
        $kernelService, 
        $securityContext,
        $refDataHelper,
        $profilePictureDirPath,
        $profilePictureDirUrl){
            $this->entityManager = $entityManager;
        $this->kernelService = $kernelService;
        $this->securityContext = $securityContext;
        $this->profilePictureDirPath = $profilePictureDirPath;
        $this->profilePictureDirUrl = $profilePictureDirUrl;
        $this->refDataHelper = $refDataHelper;
    }

    
	public function createIfNotExist( User $user )
    {
        $profile =  $this->entityManager->getRepository('FarolaProfileBundle:Profile')
                            ->findOneBy(array('user' => $user));

        if($profile == null)
        {
            $profile = new Profile($user);
            $this->entityManager->persist($profile);
            $this->entityManager->flush();
        }
    }

    public function getProfilePictureDirectoryPath() {

        return $this->profilePictureDirPath;
    }

    public function getProfilePictureDirectoryUrl() {
       
       return $this->profilePictureDirUrl;
    }

    public function createProfPictureTmp($data, $profile) {
        $date =new \Datetime();
        $filename = $profile->getId().$date->format('YmdHis').'.jpg';
        $fullFilePath = $this->getProfilePictureDirectoryPath().'/tmp/'.$filename;
        
        file_put_contents($fullFilePath, $data);

        if (filesize($fullFilePath) > 100000)
        {
            return null;
        }

        return $filename;
    }

    public function getCurrentUserProfile()
    {
        return $this->securityContext->getToken()->getUser()->getCurrentProfile();
    }

    public function replaceProfilePicture($oldFn,$newFn) {
        $this->deleteProfilePicture($oldFn);
        rename($this->getProfilePictureDirectoryPath().'/tmp/'.$newFn, $this->getProfilePictureDirectoryPath().'/'.$newFn);
    }

    public function deleteProfilePicture($oldFn) {
        if($oldFn != null 
            && $oldFn != Profile::DEFAULT_PROF_PIC_FN 
            && file_exists($this->getProfilePictureDirectoryPath().'/'.$oldFn))
            unlink($this->getProfilePictureDirectoryPath().'/'.$oldFn);
    }

    
    public function saveProfilePicture($data, $profile) {
        $newFn = $this->createProfPictureTmp($data, $profile);
        if ($newFn == null){
            return false;
        }
       $oldFn = $profile->getProfilePictureFilename();
       $this->replaceProfilePicture($oldFn,$newFn);
       $profile->setProfilePictureFilename($newFn);
       return true;
    }

    public function getProfilePictureUrl($profile){
        return $this->getProfilePictureDirectoryUrl().'/'.$profile->getProfilePictureFilename();
    }

    public function removeProfilePicture($profile){
        $this->deleteProfilePicture($profile->getProfilePictureFilename());
        $profile->setProfilePictureFilename($profile::DEFAULT_PROF_PIC_FN);
    }

    public function getAge($profile) {
        if ($profile->getDateOfBirth() == null)
            return null;

        return $profile->getDateOfBirth()->diff(new \DateTime('today'))->y;
    }

    public function getGenderName($profile) {
        if ($profile->getGender() == null)
            return null;

        switch ($profile->getGender()) {
            case 'M':
                 return 'Male';
            case 'F':
                 return 'Female';
         }
    }

    public function getCountryName($countryCode) {
       return Intl::getRegionBundle()->getCountryName($countryCode,'en'); 
    }

    public function getLanguageLvlArr($profile) {
        
        $spokenLanguages = $profile->getSpokenLanguages();
        
        if ($spokenLanguages == null || empty($spokenLanguages))
            return null;

        $result = [];

        foreach ($spokenLanguages as $languageLvlStr)
        {
            list($language, $lvl) = preg_split('/:/', $languageLvlStr, 2);
            $result[] = array('language' => $this->refDataHelper->getLanguageName($language),
             'level' => $lvl);
        }

        return $result;
    }

    public function getLanguageLevelsRef()
    {
        return json_decode(file_get_contents(__DIR__.'/../Resources/ref_data/languagelevels.json'), true);
    }

    public function isUserProfile($profile) {
        return ($this->securityContext->getToken()->getUser() == $profile->getUser());
    }

    public function hasReviewed($reviewer, $subject) {
        return $this->entityManager->getRepository('FarolaProfileBundle:Review')
            ->exists($reviewer, $subject);
    }

    public function hasContact($relOwner, $contact) {
        return is_object($this->entityManager->getRepository('FarolaProfileBundle:Relation')
                            ->findOneBy(array(
                                'relOwner' => $relOwner, 
                                'relatedProfile' => $contact, 
                                'relationType'=> Relation::TYPE_CONTACT)));
    }

    public function hasIgnored($relOwner, $ignoredProfile) {
        return is_object($this->entityManager->getRepository('FarolaProfileBundle:Relation')
                            ->findOneBy(array(
                                'relOwner' => $relOwner, 
                                'relatedProfile' => $ignoredProfile, 
                                'relationType'=> Relation::TYPE_IGNORE)));
    }

    public function getFormatedMetadata($profile){
        $compo = array();
        if ($profile->getCountryOfOrigin())
        {
            $compo[] = "From <b>".$this->getCountryName($profile->getCountryOfOrigin())."</b>";
        }
        if ($profile->getDateOfBirth())
        {
            $compo[] = " <b>".$this->getAge($profile)." years old</b>";
        }
        if ($profile->getGender())
        {
            $compo[] = " <b>".$this->getGenderName($profile)."</b>";
        }
        return implode(',', $compo);
    }

    public function getSpokenLanguagesOver($profile, $minLevel) {
        $languages = array();
        foreach ($profile->getSpokenLanguages() as $languageLvl) {
            $languageLvlArr = explode(':', $languageLvl);
            if (intval($languageLvlArr[1]) >= $minLevel)
            {
                $languages[]=$languageLvlArr[0];
            }
        }
        return $languages;
    }

}
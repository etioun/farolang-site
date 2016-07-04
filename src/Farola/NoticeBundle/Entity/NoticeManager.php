<?php

namespace Farola\NoticeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\EntityRepository;

class NoticeManager
{
	protected $em;

	protected $cc;

	protected $ph;

	public function __construct($em, $cc, $ph){
		$this->em = $em;
		$this->cc = $cc;
		$this->ph = $ph;
	}

	public function save($notice) {

		if (isset($notice)=== false)
			return false;

		$category = $notice->getCategory();

		if (isset($category) === false)
			return false;

		if($category == Notice::CAT_TEACHING)
		{
			$notice->setLearnedLanguage(null);
		}
		elseif ($category == Notice::CAT_STUDENT)
		{
			$notice->setTeachedLanguage(null);
		}

		if ($category == Notice::CAT_TEACHING
			|| $category == Notice::CAT_STUDENT)
		{
			$notice->setEuroPrice(
				$this->cc->convert($notice->getLocalPrice(), 'EUR', true, $notice->getLocalCurrency()));
		}
		else
		{
			$notice->setLocalPrice(null);
			$notice->setLocalCurrency(null);
			$notice->setEuroPrice(null);
		}

		if ($category == Notice::CAT_LANG_EX)
		{
			$notice->setTeachedLanguage($this->ph->getSpokenLanguagesOver($notice->getProfile(), 3));
		}

		if ($notice->getAvailableAnytime()) {
			$notice->setAvailabilities(null);
		}

		// if($notice->getOnlineService() == false)
		// {
		// 	$this->address = null;
	 //        $this->longitude = null;
	 //        $this->latitude = null;
	 //        $this->country = null;
	 //        $this->placeId = null;
		// }

		$this->em->persist($notice);
		$this->em->flush();
		return true;
	}
}
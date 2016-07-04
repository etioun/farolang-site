<?php

namespace Farola\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

class MessageHelper 
{
	protected $em;

	public function __construct($em) {
		$this->em = $em;
	}

	public function sendGeneralMessage($message, $sender, $receiver) {

		$thread = $this->em->getRepository('FarolaMessageBundle:Thread')
			->findGeneral($sender, $receiver);

		if ($thread == null) {
			//can t create a new thread by sending a message to yourself
			if ($sender->getId() == $receiver->getId())
				return;

			$thread = new Thread();
			$thread->setCategory(Thread::CAT_GENERAL);
			$thread->addmetadatum(new ThreadMetadata($thread,$sender, true));
			$thread->addmetadatum(new ThreadMetadata($thread,$receiver, false));
		}

		$this->sendMessage($message, $sender,$thread);
	}

	public function sendNoticeMessage($message, $sender, $noticeId) {

		$thread = $this->em->getRepository('FarolaMessageBundle:Thread')
			->findNotice($sender, $noticeId);

		if ($thread == null) {
			$notice = $this->em->getRepository('FarolaNoticeBundle:Notice')
				->findOneById($noticeId);

			//can t create a new thread by sending a message to yourself
			if ($notice->getProfile()->getId() == $sender->getId())
				return;

			$thread = new Thread();
			$thread->setCategory(Thread::CAT_NOTICE);
			$thread->setNotice($notice);
			$thread->addmetadatum(new ThreadMetadata($thread,$sender, true));
			$thread->addmetadatum(new ThreadMetadata($thread,$notice->getProfile(), false));
		}
		
		$this->sendMessage($message, $sender,$thread);
	}

	public function sendMessageWithThreadId($message, $sender,  $threadId) {

		$thread = $this->em->getRepository('FarolaMessageBundle:Thread')
			->findOneById($threadId);

		$this->sendMessage($message, $sender, $thread);
	}

	public function sendMessage($message, $sender, $thread) {

		if ($thread->getLastSender() != $sender) {
			foreach ($thread->getMetadata() as $metadata) {
				if ($metadata->getParticipant() == $sender) {
					$metadata->setIsRead(true);
				}
				else {
					$metadata->setIsRead(false);
				}
			}
		}
		$thread->addMessage(new Message($message, $thread, $sender));
		$thread->setLastSender($sender);
		$thread->setLastMessageBodyShort(substr($message,0,50));

		$this->em->persist($thread);
		$this->em->flush();
	}

	public function getOtherParticipant($thread, $participant){
		foreach ($thread->getMetadata() as $metadata) {
			if($metadata->getParticipant() != $participant) {
				return $metadata->getParticipant();
			}
		}
	}

	public function markAsRead($thread, $reader) {
		foreach ($thread->getMetadata() as $metadata) {
			if ($metadata->getParticipant()->getId() == $reader->getId()) {
				$metadata->setIsRead(true);
				break;
			}
		}
		$this->em->persist($thread);
		$this->em->flush();
	}

	public function isRead($thread, $profile) {
		foreach ($thread->getMetadata() as $metadata) {
			if ($metadata->getParticipant()->getId() == $profile->getId()) {
				return $metadata->getIsRead();
			}
		}
	}

	public function nbUnread($profile, $category = null) {
		return $this->em->getRepository('FarolaMessageBundle:Thread')
			->countUnread($profile, $category);
	}

	public function nbUnreadGeneral($profile) {
		return $this->nbUnread($profile, Thread::CAT_GENERAL);
	}

	public function nbUnreadNoticeReply($profile) {
		return $this->nbUnread($profile, Thread::CAT_NOTICE);
	}


}
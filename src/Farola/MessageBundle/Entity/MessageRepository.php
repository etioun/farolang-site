<?php

namespace Farola\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class MessageRepository extends EntityRepository 
{
	public function getPaginatorByThreadAndParticipant($threadId, $participant, $firstResult, $limit=10) {
		$query = $this->getEntityManager()
	        ->createQuery('
	            SELECT m
	            FROM FarolaMessageBundle:Message m
	            JOIN m.thread t
	            WHERE 
					t.id = :threadId
		            AND exists(
		            	SELECT 1 
		            	FROM FarolaMessageBundle:ThreadMetadata tm 
		            	WHERE tm.thread = :threadId and tm.participant = :participant)
				ORDER BY m.createdAt DESC'
	        )->setParameters(array('threadId' => $threadId,'participant' => $participant));
		$query->setFirstResult($firstResult);
	    $query->setMaxResults($limit);

	    return new Paginator($query);
	}
}
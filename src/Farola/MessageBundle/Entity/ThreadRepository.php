<?php

namespace Farola\MessageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;


class ThreadRepository extends EntityRepository 
{
	public function findByParticipant($participant, $limit=3) {
		$query = $this->getEntityManager()
	        ->createQuery('
	            SELECT t 
	            FROM FarolaMessageBundle:Thread t
	            WHERE 
				exists(
	            	SELECT 1 
	            	FROM FarolaMessageBundle:ThreadMetadata tm 
	            	WHERE t.id = tm.thread and tm.participant = :participant)
				ORDER BY t.lastMessageAt DESC'
	        )->setParameters(array('participant' => $participant));

	        $query->setMaxResults($limit);

	    	return $query->getResult();
	}


	public function findGeneral($sender, $receiver) {
		$query = $this->getEntityManager()
	        ->createQuery('
	            SELECT t 
	            FROM FarolaMessageBundle:Thread t
	            WHERE 
				t.category = :category
	            AND exists(
	            	SELECT 1 
	            	FROM FarolaMessageBundle:ThreadMetadata tm 
	            	WHERE t.id = tm.thread and tm.participant = :participant1)
				AND exists(
					SELECT 1 
	            	FROM FarolaMessageBundle:ThreadMetadata tm2 
	            	WHERE t.id = tm2.thread and tm2.participant = :participant2)'
	        )->setParameters(array('category'=>Thread::CAT_GENERAL, 'participant1' => $sender, 'participant2' =>$receiver));

	    	$results = $query->getResult();

	    	if(empty($results) == false)
	    	{
	    		return $results[0];
	    	}
	    	return null;
	}

	public function findNotice($sender, $noticeId) {
		$query = $this->getEntityManager()
	        ->createQuery('
	            SELECT t 
	            FROM FarolaMessageBundle:Thread t
	            WHERE 
					t.notice = :noticeId
				AND	exists(
	            	SELECT 1 
	            	FROM FarolaMessageBundle:ThreadMetadata tm 
	            	WHERE t.id = tm.thread and tm.participant = :participant1)
				'
	        )->setParameters(array(
	        	'noticeId'=> $noticeId,
	        	'participant1' => $sender));

	    try {
	    	return $query->getSingleResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
        	return null;
    	}
	}

	public function getPaginatorByParticipantAndCategory($participant, $category, $firstResult, $limit = 10) {
		$query = $this->getEntityManager()
	        ->createQuery('
	            SELECT t, tm, p
	            FROM FarolaMessageBundle:Thread t
	            JOIN t.metadata tm
	            JOIN tm.participant p
	            WHERE exists(
	            	SELECT 1
	            	FROM FarolaMessageBundle:ThreadMetadata tm2
	            	WHERE t.id = tm2.thread and tm2.participant = :participant)
	        	AND t.deletedAt is null
	        	AND t.category = :category
	        	AND not exists(
					SELECT 1 
					FROM FarolaProfileBundle:Relation r
					JOIN FarolaMessageBundle:ThreadMetadata tm3
					WITH tm3.participant = r.relatedProfile
					AND r.relationType = 1
					AND r.relOwner = :participant
					WHERE t.id = tm3.thread)
	        	ORDER BY t.lastMessageAt DESC'
	        )
	        ->setParameters(array('participant' => $participant, 'category'=>$category));
	    $query->setFirstResult($firstResult);
	    $query->setMaxResults($limit);

	    return new Paginator($query);
	}


	public function countUnread($participant, $category = null) {
		
		$qb = $this->getEntityManager()->createQueryBuilder();
		$parameters = array('participant' => $participant);

		$qb->select('count(DISTINCT t.id)')
	    	->from('FarolaMessageBundle:Thread', 't')
	    	->join('t.metadata','tm')
	    	->join('t.metadata','tm2')
	    	->andWhere('tm.isRead = false')
	    	->andWhere('t.deletedAt is null')
	    	->andWhere('tm.participant = :participant')
	    	->andWhere('not exists( 
	    					SELECT 1 FROM FarolaProfileBundle:Relation r 
	    					WHERE r.relatedProfile = tm2.participant 
	    					and r.relOwner = :participant 
	    					AND r.relationType = 1)');

	    if (isset($category))
	    {
	    	$qb->andWhere('t.category = :category');
	    	$parameters['category'] = $category;
	    }

	    $qb->setParameters($parameters);
	    try {
	    	return $qb->getQuery()->getSingleScalarResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
        	return 0;
    	}
	}
}
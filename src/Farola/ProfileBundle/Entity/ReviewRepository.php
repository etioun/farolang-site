<?php

namespace Farola\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ReviewRepository extends EntityRepository 
{
	public function getPaginatorFindBySubject($subjectId, $firstResult, $limit = 10){
		$query = $this->getEntityManager()
	        ->createQuery('
	        	SELECT r,w,br
	        	FROM FarolaProfileBundle:Review r
	        	JOIN r.writer w
	        	LEFT JOIN r.backReview br
	        	WHERE 
	        		r.subject = :subjectId
	        	ORDER BY r.createdAt DESC'
	        )->setParameters(array('subjectId'=>$subjectId));
	    $query->setFirstResult($firstResult);
	    $query->setMaxResults($limit);
	    return new Paginator($query, true);
	}

	public function countFor($subject) {
		if (isset($subject) == false)
			return null;

		$id;

		if ($subject instanceof Profile)
		{
			$id = $subject->getId();
		}
		else
		{
			$id = $subject; 
		}

		$query = $this->getEntityManager()
	        ->createQuery('
	            SELECT count(r.id)
	            FROM FarolaProfileBundle:Review r
	            WHERE 
					r.subject = :subjectId'
	        )->setParameters(array('subjectId' => $id));

	    try {
	    	return $query->getSingleScalarResult();	
	    } catch (\Doctrine\ORM\NoResultException $e) {
        	return 0;
    	}
	}

	public function exists($writer, $subject) {
		
		$query = $this->getEntityManager()
	        ->createQuery('
	            SELECT 1
	            FROM FarolaProfileBundle:Review r
	            WHERE 
					r.subject = :subjectId
					AND r.writer = :writerId'
	        )->setParameters(array('writerId' => $writer->getId(),'subjectId' => $subject->getId()))
	        ->setMaxResults(1);
	    try {
	    	$query->getSingleScalarResult();
	    	return true;	
	    } catch (\Doctrine\ORM\NoResultException $e) {
        	return false;
    	}
	}

}
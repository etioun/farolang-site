<?php

namespace Farola\ProfileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProfileRepository extends EntityRepository 
{
	public function getPaginatorSearch($searchCrits = null, $firstResult = 0, $limit = 10) {

		$qb = $this->getEntityManager()->createQueryBuilder();
		$params = [];
	    
	    $qb->select('p')
	    	->from('FarolaProfileBundle:Profile', 'p');
	    
	    if (empty($searchCrits['name']) == false){
	    	$qb->andWhere($qb->expr()->like('p.name', ':name'));
	    	$params['name'] = '%'.$searchCrits['name'].'%';
	    }

	    if (empty($searchCrits['spokenLanguage']) == false){
	    	$qb->andWhere($qb->expr()->like('p.spokenLanguages', ':spokenLanguage'));
	    	$params['spokenLanguage'] = '%'.$searchCrits['spokenLanguage'].'%';
	    }

	    if (empty($searchCrits['country']) == false){
	    	$qb->andWhere('p.country = :country');
	    	$params['country'] = $searchCrits['country'];
	    }

	    if (empty($searchCrits['locationBB']) == false){
	    	$qb->andWhere('p.longitude BETWEEN :minLon AND :maxLon');
	    	$qb->andWhere('p.latitude BETWEEN :minLat AND :maxLat');
	    	$params['minLon'] = $searchCrits['locationBB']->minLon;
	    	$params['maxLon'] = $searchCrits['locationBB']->maxLon;
	    	$params['minLat'] = $searchCrits['locationBB']->minLat;
	    	$params['maxLat'] = $searchCrits['locationBB']->maxLat;
	    }

	    if (empty($searchCrits['reviews']) == false){
	    	$qb->andWhere('p.aggReviewCount >= :reviews');
	    	$params['reviews'] = $searchCrits['reviews'];
	    }

	    if (empty($params) == false){
	    	$qb->setParameters($params);
	    }

	    $qb->addOrderBy('p.aggReviewCount', 'DESC');
	    $qb->addOrderBy('p.updatedAt', 'DESC');

	    $query = $qb->getQuery();
	    $query->setFirstResult($firstResult);
	    $query->setMaxResults($limit);

	    return new Paginator($query);
	}

	public function getContactsOf($profile) {
		if (is_object($profile) == false)
			return;

		$query = $this->getEntityManager()
	        ->createQuery('
	            SELECT p
	            FROM FarolaProfileBundle:Relation r
	            JOIN FarolaProfileBundle:Profile p WITH p.id = r.relatedProfile
	            WHERE
					r.relOwner = :relOwner
					and r.relationType = 0'
	        )->setParameters(array('relOwner' => $profile->getId()));

	    try {
	    	return $query->getResult();
	    } catch (\Doctrine\ORM\NoResultException $e) {
        	return null;
    	}
	}
}
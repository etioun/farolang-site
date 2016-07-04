<?php

namespace Farola\NoticeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;


class NoticeRepository extends EntityRepository 
{
	public function recentlyUpdated($limit = 3){
		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('n,p')
	    	->from('FarolaNoticeBundle:Notice', 'n')
	    	->join('n.profile','p');
	    $qb->andWhere('n.activated = true');
	    $qb->addOrderBy('n.updatedAt', 'DESC');
	    $query = $qb->getQuery();
	    $query->setMaxResults($limit);

	    return $query->getResult();
	}

	public function getPaginatorSearch($searchCrits = null, $firstResult, $limit = 10) {

		$qb = $this->getEntityManager()->createQueryBuilder();
		$params = [];

	    $qb->select('n,p')
	    	->from('FarolaNoticeBundle:Notice', 'n')
	    	->join('n.profile','p');
	    $qb->andWhere('n.activated = true');
	    $qb->addOrderBy('p.aggReviewCount', 'DESC');
	    
	    if (isset($searchCrits['category'])){
	    	$qb->andWhere('n.category = :category');
	    	$params['category'] = $searchCrits['category'];
	    }

	    if (isset($searchCrits['categories'])){
	    	$qb->andWhere($qb->expr()->in('n.category', ':categories'));
	    	$params['categories'] = $searchCrits['categories'];
	    }

	    if (isset($searchCrits['teachedLanguage'])){
	    	$qb->andWhere('n.teachedLanguage like :teachedLanguage');
	    	$params['teachedLanguage'] = '%'.$searchCrits['teachedLanguage'].'%';
	    }

	    if (isset($searchCrits['learnedLanguage'])){
	    	$qb->andWhere('n.learnedLanguage like :learnedLanguage');
	    	$params['learnedLanguage'] = '%'.$searchCrits['learnedLanguage'].'%';
	    }

	    if (isset($searchCrits['involvedLanguage'])){
	    	$qb->andWhere('( n.learnedLanguage like :involvedLanguage) or ( n.teachedLanguage like :involvedLanguage)');
	    	$params['involvedLanguage'] = '%'.$searchCrits['involvedLanguage'].'%';
	    }

	    if (isset($searchCrits['spokenLanguage'])){
	    	$qb->andWhere($qb->expr()->like('p.spokenLanguages', ':spokenLanguage'));
	    	$params['spokenLanguage'] = '%'.$searchCrits['spokenLanguage'].'%';
	    }
	   	if (isset($searchCrits['locationBB'])){
	    	$qb->andWhere('n.longitude BETWEEN :minLon AND :maxLon');
	    	$qb->andWhere('n.latitude BETWEEN :minLat AND :maxLat');
	    	$params['minLon'] = $searchCrits['locationBB']->minLon;
	    	$params['maxLon'] = $searchCrits['locationBB']->maxLon;
	    	$params['minLat'] = $searchCrits['locationBB']->minLat;
	    	$params['maxLat'] = $searchCrits['locationBB']->maxLat;

	    }
	    if (isset($searchCrits['onlineService'])){
	    	$qb->andWhere('n.onlineService = :onlineService');
	    	$params['onlineService'] = $searchCrits['onlineService'];
	    }
	    if (isset($searchCrits['profile_country'])){
	    	$qb->andWhere('p.country = :profile_country');
	    	$params['profile_country'] = $searchCrits['profile_country'];
	    }
	    if (isset($searchCrits['tags'])){
	    	foreach ($searchCrits['tags'] as $tag) {
	    		$param =  ':tag'.$tag;
	    		$qb->andWhere($qb->expr()->like('n.tags', $param));
	    		$params[$param] = '%'.$tag.'%';
	    	}
	    }
	    if (isset($params)){
	    	$qb->setParameters($params);
	    }

	    $query = $qb->getQuery();
		$query->setFirstResult($firstResult);
	    $query->setMaxResults($limit);

	    return new Paginator($query);
	}

	public function count($category, $profile) {
		$query = $this->getEntityManager()
	        ->createQuery('
	            SELECT count(n.id)
	            FROM FarolaNoticeBundle:Notice n
	            WHERE
					n.profile = :profile
					AND n.category = :category'
	        )
	        ->setParameters(array(
	        	'profile' => $profile,
	        	'category' => $category
	        ))
	    ;

	    try {
	    	return $query->getSingleScalarResult();	
	    } catch (\Doctrine\ORM\NoResultException $e) {
        	return 0;
    	}
	}
}
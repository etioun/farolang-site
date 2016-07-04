<?php

namespace Farola\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;


class UserRepository extends EntityRepository 
{
	public function getUnnotifiedUsersWithUnreadMessages(){
		$qb = $this->getEntityManager()->createQueryBuilder();
		$qb->select('DISTINCT u')
	    	->from('FarolaUserBundle:User', 'u');
	    $qb->andWhere('u.enabled = 1');
	    $qb->andWhere('u.locked = 0');
	    $qb->andWhere('u.expired = 0');
	    $qb->andWhere('u.acceptNotification = 1');
	    $qb->andWhere('exists(
	    					SELECT 1
	    					FROM FarolaMessageBundle:Thread t
	    					JOIN t.metadata tm
	    					JOIN tm.participant p
	    					WHERE p.user = u.id
	    					AND u.lastMailNotificationAt < t.lastMessageAt
	    					AND tm.participant = p.id
	    					AND tm.isRead = false )');
	    $query = $qb->getQuery();

	    return $query->getResult();
	}

}
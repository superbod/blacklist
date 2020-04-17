<?php

namespace AppBundle\Repository;

use AppBundle\Entity\BlackList;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getWhiteList()
    {
        $userIds = $this->getBlackUserIDs();

        return $this->getEntityManager()->createQueryBuilder()
            ->select("u.id, u.email, u.username")
            ->from(User::class,'u')
            ->where("u.id NOT in(:users)")
            ->setParameter("users", $userIds)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    public function getBlackUserIDs()
    {
        $users =  $this->getEntityManager()->createQueryBuilder()
            ->select("b.userId")
            ->from(BlackList::class, 'b')
            ->getQuery()
            ->getResult();

        return $users;
    }
}
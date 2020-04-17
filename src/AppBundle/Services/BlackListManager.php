<?php

namespace AppBundle\Services;

use AppBundle\Entity\BlackList;
use AppBundle\Entity\User;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bridge\Doctrine\RegistryInterface;

class BlackListManager
{
    /** @var RegistryInterface */
    private $doctrine;

    /**
     * BlackListManager constructor.
     * @param RegistryInterface $doctrine
     */
    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param int $userId
     * @throws OptimisticLockException
     */
    public function addUserToBlacklist($userId)
    {
        $user = $this->doctrine->getRepository(User::class)->findOneBy(['id' => $userId]);

        $newRow = New BlackList();
        $newRow->setUserId($userId);
        $newRow->setUser($user);

        $entityManager = $this->doctrine->getEntityManager();
        $entityManager->persist($newRow);
        $entityManager->flush();
    }

    /**
     * @param $userId
     * @throws OptimisticLockException
     */
    public function removeUserFromBlacklist($userId)
    {
        $blacklistRow = $this->doctrine->getRepository(BlackList::class)->findOneBy(['userId' => $userId]);

        $entityManager = $this->doctrine->getEntityManager();
        $entityManager->remove($blacklistRow);
        $entityManager->flush();
    }
}
<?php

namespace App\Repository;

use App\Entity\Pro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Pro|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pro|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pro[]    findAll()
 * @method Pro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pro::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $pro, string $newEncodedPassword): void
    {
        if (!$pro instanceof Pro) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($pro)));
        }

        $pro->setPassword($newEncodedPassword);
        $this->_em->persist($pro);
        $this->_em->flush();
    }

    // /**
    //  * @return Pro[] Returns an array of Pro objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pro
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Grinder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

class GrinderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Grinder::class);
    }

    public function findOrCreateByUserAndMac(Uuid $userId, string $mac): Grinder
    {
        /** @var Grinder|null $grinder */
        $grinder = $this->findOneBy(['userId' => $userId, 'mac' => $mac]);

        if ($grinder) {
            return $grinder;
        }

        $grinder = new Grinder($userId, $mac);
        $this->getEntityManager()->persist($grinder);

        return $grinder;
    }
}

<?php

namespace Plugin\ProductShortCode\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Eccube\Repository\AbstractRepository;
use Plugin\ProductShortCode\Entity\Option;


class OptionRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Option::class);
    }

    /**
     * @param int $id
     *
     * @return null|Config
     */
    public function get($id = 1)
    {
        return $this->find($id);
    }
}

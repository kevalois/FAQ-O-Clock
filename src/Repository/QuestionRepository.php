<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Question::class);
    }
    
    public function findByIsBlockedAndTagOrderByVotes($blockedFilter, $tag, $start, $perPage)
    {
        // Requête de base
        $query = $this->createQueryBuilder('q')
        ->leftJoin('q.tags', 't')
        ->addOrderBy('q.votes', 'DESC')
        ->addOrderBy('q.createdAt', 'DESC');
        
        // Si tag présent
        if ($tag) {
            $query->andWhere('t = :tag')
            ->setParameter('tag', $tag);
        }
        
        // Si filtre isBlocked présent
        if ($blockedFilter) {
            $query->andWhere('q.isBlocked = false');
        }

        // Ajout Paginator
        $query->setFirstResult($start * $perPage)->setMaxResults($perPage);
            
        return new Paginator($query);
    }
}

<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * Recherche un article par titre, contenu ou nom de catégorie
     */
    public function findBySearch(string $term)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.category', 'c') // Jointure avec la catégorie
            ->andWhere('a.title LIKE :term OR a.content LIKE :term OR c.name LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère une liste d'articles paginée
     */
    public function findPaginated(int $page, int $limit = 6, array $criteria = []): array
    {
        $offset = ($page - 1) * $limit;

        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        foreach ($criteria as $field => $value) {
            $qb->andWhere("a.$field = :$field")
               ->setParameter($field, $value);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Compte le nombre total d'articles (utile pour la pagination)
     */
    public function countArticles(array $criteria = []): int
    {
        $qb = $this->createQueryBuilder('a')
            ->select('count(a.id)');

        foreach ($criteria as $field => $value) {
            $qb->andWhere("a.$field = :$field")
               ->setParameter($field, $value);
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
}

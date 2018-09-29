<?php

namespace App\Repository;

use App\Entity\Author;
use App\Entity\Postcard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Postcard|null find($id, $lockMode = null, $lockVersion = null)
 * @method Postcard|null findOneBy(array $criteria, array $orderBy = null)
 * @method Postcard[]    findAll()
 * @method Postcard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostcardRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Postcard::class);
    }

    /**
     * @return Postcard[] Returns an array of Postcards objects
     *
     * Последние открытки - разбиение по страницам
     */
    public function findLatest(int $page = 1): Pagerfanta
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT p
                FROM App:Postcard p
                ORDER BY p.id DESC
            ')
        ;

        return $this->createPaginator($query, $page);
        /*        return $this->createQueryBuilder('g')
                    ->orderBy('g.date', 'DESC')
                    ->getQuery()
                    ->getResult()
                ;*/
    }

    /**
     * @return Postcard[] Returns an array of Postcards objects
     *
     * Разбиение по страница для каталога одного автора
     */

    public function findLatestByAuthor(Author $author, int $page = 1): Pagerfanta
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT p
                FROM App:Postcard p
                WHERE p.author = :author
                ORDER BY p.id DESC
            ')
            ->setParameter('author', $author)
        ;

        return $this->createPaginator($query, $page);
        /*        return $this->createQueryBuilder('g')
                    ->orderBy('g.date', 'DESC')
                    ->getQuery()
                    ->getResult()
                ;*/
    }

    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(Postcard::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

// примеры от Symfony - сохраняю для справки
//    /**
//     * @return Postcard[] Returns an array of Postcard objects
//     */
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
    public function findOneBySomeField($value): ?Postcard
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

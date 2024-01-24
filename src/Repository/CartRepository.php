<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 *
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }
    
    /**
     * @return Article[] Returns an array of Article objects for the given user ID
     */
    public function findAllArticlesByUserId($userId): array
    {
        return $this->createQueryBuilder('c')
            ->select('a')
            ->leftJoin(Article::class, 'a', 'WITH', 'a.id = c.articleId')
            ->andWhere('c.userId = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY)
        ;
    }
    
    /**
     * Deletes a cart by ID.
     *
     * @param int $cartId The ID of the cart to delete
     * @return void
     */
    public function deleteCart($cartId): void
    {
        $cart = $this->find($cartId);
        
        if ($cart) {
            $this->_em->remove($cart);
            $this->_em->flush();
        }
    }

//    /**
//     * @return Cart[] Returns an array of Cart objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Cart
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

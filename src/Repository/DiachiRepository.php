<?php

namespace App\Repository;

use App\Entity\Diachi;
use App\Entity\Phuong;
use App\Entity\Quan;
use App\Entity\Thanhpho;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Diachi>
 */
class DiachiRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;
    private PhuongRepository $phuongRepository;
    private QuanRepository $quanRepository;
    private ThanhphoRepository $thanhphoRepository;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, PhuongRepository $phuongRepository, QuanRepository $quanRepository, ThanhphoRepository $thanhphoRepository)
    {
        parent::__construct($registry, Diachi::class);
        $this->entityManager = $entityManager;
        $this->phuongRepository = $phuongRepository;
        $this->quanRepository = $quanRepository;
        $this->thanhphoRepository = $thanhphoRepository;
    }

    public function findAllDiachi(): array
    {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.phuong', 'p')
            ->leftJoin('d.quan', 'q')
            ->leftJoin('d.thanhpho', 't')
            ->getQuery()
            ->getResult();
    }

    public function createDiachi(Phuong $phuong, Quan $quan, Thanhpho $thanhpho, User $user_id, string $address)
    {
        $diachi = new Diachi();
        $diachi->setPhuong($phuong);
        $diachi->setQuan($quan);
        $diachi->setThanhpho($thanhpho);
        $diachi->setUser($user_id);
        $diachi->setDiachi($address);

        $this->entityManager->persist($diachi);
        $this->entityManager->flush();

        return $diachi;
    }

    public function deleteDiachi(int $id)
    {
        $diachi = $this->find($id);

        if (!$diachi) {
            return false;
        }

        $this->entityManager->remove($diachi);
        $this->entityManager->flush();

        return true;
    }

    public function updateDiachi(int $id, array $data)
    {
        $diachi = $this->find($id);

        if (!$diachi) {
            return null;
        }

        $phuongObject = $this->phuongRepository->find($data['phuong_id']);
        $quanObject = $this->quanRepository->find($data['quan_id']);
        $thanhphoObject = $this->thanhphoRepository->find($data['thanhpho_id']);


        if (isset($phuongObject)) {
            $diachi->setPhuong($phuongObject);
        }

        if (isset($quanObject)) {
            $diachi->setQuan($quanObject);
        }

        if (isset($thanhphoObject)) {
            $diachi->setThanhpho($thanhphoObject);
        }
        if (isset($data['diachi'])) {
            $diachi->setDiachi($data['diachi']);
        }

        $this->entityManager->flush();

        return $diachi;
    }

    //    /**
    //     * @return Diachi[] Returns an array of Diachi objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Diachi
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

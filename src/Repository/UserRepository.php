<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, private UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $entityManager;
    }

    public function createUser(string $hoten, string $username, string $password): User
    {
        $user = new User();
        $user->setHoten($hoten);
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setRoles(['ROLE_USER']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function updateUser(int $id, array $data): ?User
    {
        // Tìm User theo ID
        $user = $this->find($id);

        if (!$user) {
            // Nếu không tìm thấy User, trả về null
            return null;
        }

        // Cập nhật các thuộc tính
        if (isset($data['username'])) {
            $user->setUsername($data['username']);
        }

        if (isset($data['hoten'])) {
            $user->setHoten($data['hoten']);
        }

        if (isset($data['password'])) {
            // Hash password trước khi lưu
            $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        // Ghi thay đổi vào cơ sở dữ liệu
        $this->entityManager->flush();

        return $user;
    }

    public function deleteUser(int $id)
    {
        $user = $this->find($id);

        if (!$user) {
            return false;
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return true;
    }

    public function createAdmin(string $hoten, string $username, string $password): User
    {
        $user = new User();
        $user->setHoten($hoten);
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setRoles(['ROLE_ADMIN']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }






    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

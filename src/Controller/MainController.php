<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\DiachiRepository;
use App\Repository\ThanhphoRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/')]
    public function homepage(ThanhphoRepository $thanhphoRepository): Response
    {
        $thanhpho = $thanhphoRepository->findAll();

        return $this->json($thanhpho);
    }

    #[Route('/api/user', methods: ['POST'])]
    public function createUser(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $hoten = $data['hoten'] ?? null;
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        if (!$hoten || !$username || !$password) {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        // Hash mật khẩu
        $hashedPassword = $passwordHasher->hashPassword(new User(), $password);

        // Tạo người dùng
        $user = $userRepository->createUser($hoten, $username, $hashedPassword);

        return $this->json([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'password' => 'Hashed and secured',
        ]);
    }

    #[Route('api/user/{id}', methods: ['PATCH'])]
    public function updateUser(int $id, Request $request, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        // Kiểm tra quyền EDIT
        $this->denyAccessUnlessGranted('EDIT', $user);

        $data = json_decode($request->getContent(), true);
        $user = $userRepository->updateUser($id, $data);

        return $this->json([
            'id' => $user->getId(),
            'hoten' => $user->getHoten(),
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
        ]);
    }

    #[Route('api/user/{id}', methods: ['DELETE'])]
    public function deleteUser(int $id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find($id);

        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        // Kiểm tra quyền DELETE
        $this->denyAccessUnlessGranted('DELETE', $user);

        $userRepository->deleteUser($id);

        return $this->json(['message' => 'User deleted successfully']);
    }

    #[Route('api/user', methods: ['GET'])]
    public function getAllUser(UserRepository $userRepository): JsonResponse
    {
        // Kiểm tra quyền admin
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        $user = $userRepository->findAll();

        $result = array_map(function ($user) {
            return [
                'id' => $user->getId(),
                'hoten' => $user->getHoten(),
                'username' => $user->getUsername(),
                'password' => $user->getPassword(),
            ];
        }, $user);

        return $this->json($result);
    }

    #[Route('api/user/{id}', methods: ['GET'])]
    public function getOneUser(int $id, UserRepository $userRepository): JsonResponse
    {
        // Kiểm tra quyền admin
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        $user = $userRepository->find($id);

        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        return $this->json([
            'id' => $user->getId(),
            'hoten' => $user->getHoten(),
            'username' => $user->getUsername(),
            'password' => $user->getPassword(),
        ]);
    }

    #[Route('api/diachi', methods: ['GET'])]
    public function getAllDiachi(DiachiRepository $diachiRepository): JsonResponse
    {
        $diachiList = $diachiRepository->findAllDiachi();

        // Chuyển dữ liệu thành mảng JSON
        $result = array_map(function ($diachi) {
            return [
                'id' => $diachi->getId(),
                'diachi' => $diachi->getDiachi(),
                'phuong' => $diachi->getPhuong()?->getTenPhuong(),
                'quan' => $diachi->getQuan()?->getTenQuan(),
                'thanhpho' => $diachi->getThanhpho()?->getTenThanhpho(),
                'user' => $diachi->getUser()?->getHoten(),
            ];
        }, $diachiList);

        return $this->json($result);
    }

    #[Route('/api/user/admin', name: 'create_admin', methods: ['POST'])]
    public function createAdmin(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $hoten = $data['hoten'] ?? null;
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        if (!$hoten || !$username || !$password) {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        // Hash mật khẩu
        $hashedPassword = $passwordHasher->hashPassword(new User(), $password);

        // Tạo người dùng
        $user = $userRepository->createAdmin($hoten, $username, $hashedPassword);

        return $this->json([
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'password' => 'Hashed and secured',
        ]);
    }
}

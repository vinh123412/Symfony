<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    //    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    //    public function login()
    //    {
    //        // Logic được xử lý bởi LexikJWTAuthenticationBundle, không cần thêm code.
    //        throw new \Exception('Should not be reached');
    //    }

    #[Route('/api/custom-login', name: 'custom_login', methods: ['POST'])]
    public function login(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        if (!$username || !$password) {
            return $this->json(['error' => 'Invalid credentials'], 400);
        }

        // Tìm user theo username
        $user = $userRepository->findOneBy(['username' => $username]);

        // Kiểm tra username và password
        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return $this->json(['error' => 'Invalid username or password'], 401);
        }

        // Tạo JWT token
        $token = $this->generateJwtToken($user, $jwtManager);

        return $this->json([
            'token' => $token,
            'user' => [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
            ],
        ]);
    }

    private function generateJwtToken(User $user, JWTTokenManagerInterface $jwtManager): string
    {
        return $jwtManager->create($user);
    }
}

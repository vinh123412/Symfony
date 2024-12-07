<?php

namespace App\Controller;

use App\Repository\DiachiRepository;
use App\Repository\PhuongRepository;
use App\Repository\QuanRepository;
use App\Repository\ThanhphoRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DiachiController extends AbstractController
{
    #[Route('/api/diachi', name: 'create_address', methods: ['POST'])]
    public function createAddress(Request $request, DiachiRepository $diachiRepository, PhuongRepository $phuongRepository, QuanRepository $quanRepository, ThanhphoRepository $thanhphoRepository, UserRepository $userRepository)
    {
        $data = json_decode($request->getContent(), true);

        $phuong = $data['phuong_id'] ?? null;
        $quan = $data['quan_id'] ?? null;
        $thanhpho = $data['thanhpho_id'] ?? null;
        $user_id = $data['user_id'] ?? null;
        $diachi = $data['diachi'] ?? null;

        // Lấy user hiện tại
        $currentUser = $this->getUser();

        // Kiểm tra user_id từ body có trùng với user hiện tại không
        if (!$user_id || $user_id !== $currentUser->getId()) {
            throw $this->createAccessDeniedException('You can only create addresses for yourself.');
        }

        // Kiểm tra quyền CREATE với người dùng hiện tại
        $this->denyAccessUnlessGranted('CREATE', $currentUser);

        // Tìm các đối tượng liên quan
        $phuongObject = $phuongRepository->find($phuong);
        $quanObject = $quanRepository->find($quan);
        $thanhphoObject = $thanhphoRepository->find($thanhpho);

        if (!$phuongObject || !$quanObject || !$thanhphoObject) {
            return $this->json(['error' => 'Invalid location data'], 400);
        }

        // Tạo địa chỉ
        $address = $diachiRepository->createDiachi($phuongObject, $quanObject, $thanhphoObject, $currentUser, $diachi);

        return $this->json([
            'phuong_id' => $address->getPhuong()->getId(),
            'quan_id' => $address->getQuan()->getId(),
            'thanhpho_id' => $address->getThanhpho()->getId(),
            'user_id' => $address->getUser()->getId(),
            'diachi' => $address->getDiachi(),
        ]);
    }

    #[Route('/api/diachi/{id}', methods: ['DELETE'])]
    public function deleteDiachi(int $id, DiachiRepository $diachiRepository)
    {
        $address = $diachiRepository->find($id);

        // Kiểm tra quyền DELETE
        $this->denyAccessUnlessGranted('DELETE', $address);

        $success = $diachiRepository->deleteDiachi($id);

        if (!$success) {
            return $this->json(['error' => 'Diachi not found'], 404);
        }

        return $this->json(['message' => 'Diachi deleted successfully'], 200);
    }

    #[Route('/api/diachi/{id}', methods: ['PATCH'])]
    public function updateDiachi(int $id, DiachiRepository $diachiRepository, Request $request)
    {
        $address = $diachiRepository->find($id);

        // Kiểm tra quyền EDIT
        $this->denyAccessUnlessGranted('EDIT', $address);

        $data = json_decode($request->getContent(), true);

        $diachi = $diachiRepository->updateDiachi($id, $data);

        if (!$diachi) {
            return $this->json(['error' => 'Diachi not found'], 404);
        }

        return $this->json([
            'phuong_id' => $diachi->getPhuong()->getId(),
            'quan_id' => $diachi->getQuan()->getId(),
            'thanhpho_id' => $diachi->getThanhpho()->getId(),
            'diachi' => $diachi->getDiachi(),
        ]);
    }
}

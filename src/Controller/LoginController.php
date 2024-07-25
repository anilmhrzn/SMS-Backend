<?php


namespace App\Controller;

use App\Dto\LoginRequestDTO;
use App\Service\JwtTokenGenerator;
use App\Service\LoginValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/api/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request, LoginValidationService $loginValidationService, JwtTokenGenerator $jwtTokenGenerator): JsonResponse
    {

        $loginDTO = new LoginRequestDTO(json_decode($request->getContent(), true)? : []);
        $validationResult = $loginValidationService->validateLoginDTO($loginDTO);
        if (isset($validationResult['errors'])) {
            return new JsonResponse(['error' => $validationResult['errors']], Response::HTTP_BAD_REQUEST, ['content-type' => 'application/json']);
        }
        $user = $validationResult['user'];
        $token = $jwtTokenGenerator->generateToken($user);
        $response = new JsonResponse(["message" => "login successful"], Response::HTTP_OK, ['content-type' => 'application/json']);
        $response->headers->set('Authorization', 'Bearer ' . $token);
        $response->headers->set('Access-Control-Expose-Headers', 'Authorization');
        return $response;
    }
}
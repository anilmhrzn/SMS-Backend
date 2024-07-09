<?php

namespace App\Controller;

use App\Service\JwtTokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class TokenController extends AbstractController
{
    public function __construct(private JwtTokenGenerator $jwtTokenGenerator)
    {

    }

    #[Route('/api/validate-token', name: 'validate_token', methods: ['GET'])]
    public function validateToken(Request $request): Response
    {
        $token = $request->headers->get('Authorization');
        if (null === $token) {
            return $this->json(['error' => 'token missing'], Response::HTTP_UNAUTHORIZED);
        }
        try {
            $isValid = $this->isTokenValid($request);

        } catch (CustomUserMessageAuthenticationException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        }
        if ($isValid) {
            return $this->json(['valid' => true]);
        } else {
            return $this->json(['valid' => false], Response::HTTP_UNAUTHORIZED);
        }
    }

    private function isTokenValid($request): bool
    {
        $payload = $this->jwtTokenGenerator->validateToken($request);
        if ($payload['exp'] < time()) {
            throw new CustomUserMessageAuthenticationException('Token has expired');
        }
        if (!isset($payload['email'])) {
            throw new CustomUserMessageAuthenticationException('Token is missing the payload email');
        }
        if (!isset($payload['id'])) {
            throw new CustomUserMessageAuthenticationException('Token is missing the payload id');
        }

        return true;
    }
}
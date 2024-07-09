<?php


namespace App\Controller;

use App\Entity\User;

//use App\Form\RegistrationFormType;
use App\Service\JwtTokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Jose\Component\Signature\Algorithm\HS256;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Jose\Component\Core\JWK;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Signature\Serializer\CompactSerializer;

use Jose\Component\Signature\JWSBuilder;

class LoginController extends AbstractController
{
    #[Route('/api/login', name: 'app_login', methods: ['POST'])]
    public function login(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher,JwtTokenGenerator $jwtTokenGenerator)
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $password = $data['password'];
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse(['error' => 'Invalid email or password'], Response::HTTP_UNAUTHORIZED, ['content-type' => 'application/json']);
        }
       $token= $jwtTokenGenerator->generateToken($user);
        $response = new JsonResponse(["message" => "login successful"], Response::HTTP_OK, ['content-type' => 'application/json']);
        $response->headers->set('Authorization', 'Bearer ' . $token);
        $response->headers->set('Access-Control-Expose-Headers', 'Authorization');
        return $response;
    }

}
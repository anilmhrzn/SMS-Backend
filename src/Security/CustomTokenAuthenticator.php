<?php

namespace App\Security;

use App\Service\JwtTokenGenerator;
use Jose\Component\Checker\AlgorithmChecker;
use Jose\Component\Checker\HeaderCheckerManager;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\JWK;
use Jose\Component\Signature\Algorithm\HS256;
use Jose\Component\Signature\JWSTokenSupport;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\Serializer\JWSSerializerManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class CustomTokenAuthenticator extends AbstractAuthenticator
{
    public function __construct(private readonly JwtTokenGenerator $jwtTokenGenerator)
    {
    }
    public function supports(Request $request): ?bool
    {
        return true;
    }

    public function authenticate(Request $request): Passport
    {

//        $algorithmManager = new AlgorithmManager([
//            new HS256(),
//        ]);
//        $jwsVerifier = new JWSVerifier($algorithmManager);
//        $jwk = new JWK([
//            'kty' => 'oct',
//            'k' => $_ENV['JWT_SECRET_KEY'],
//        ]);
//
//        $serializerManager = new JWSSerializerManager([
//            new CompactSerializer(),
//        ]);
//
//        try {
//            $jws = $serializerManager->unserialize($apiToken);
//        } catch (\InvalidArgumentException $e) {
//            throw new CustomUserMessageAuthenticationException('Invalid token');
//        }
//        $headerCheckerManager = new HeaderCheckerManager(
//            [
//                new AlgorithmChecker(['HS256']),
//                // We want to verify that the header "alg" (algorithm)
//                // is present and contains "HS256"
//            ],
//            [
//                new JWSTokenSupport(), // Adds JWS token type support
//            ]
//        );
//        try {
//            $headerCheckerManager->check($jws, 0);
//        } catch (\Exception $e) {
//            echo 'Error: ' . $e->getMessage();
//        }
//
//        if (!$jwsVerifier->verifyWithKey($jws, $jwk, 0)) {
//            throw new CustomUserMessageAuthenticationException('Invalid token signature');
//        }
//        $payload = json_decode($jws->getPayload(), true);
//        if ($payload['exp'] < time()) {
//            throw new CustomUserMessageAuthenticationException('Token has expired');
//        }
//
//        if (!isset($payload['email'])) {
//            throw new CustomUserMessageAuthenticationException('Token is missing the payload email');
//        }
//        if (!isset($payload['id'])) {
//            throw new CustomUserMessageAuthenticationException('Token is missing the payload id');
//        }

        $payload = $this->jwtTokenGenerator->validateToken($request);
        $userIdentifier = $payload['email'];

        return new SelfValidatingPassport(new UserBadge($userIdentifier));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null; // Allow the request to continue
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}

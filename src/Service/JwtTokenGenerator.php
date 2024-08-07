<?php

namespace App\Service;

use App\Entity\User;
use App\Service\Interfaces\TokenGeneratorInterface;
use Jose\Component\Checker\AlgorithmChecker;
use Jose\Component\Checker\HeaderCheckerManager;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\JWK;
use Jose\Component\Signature\Algorithm\HS256;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\JWSTokenSupport;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\Serializer\JWSSerializerManager;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class JwtTokenGenerator implements TokenGeneratorInterface
{
    public function loadUserData(User $user): array
    {
//        dd($user->getRoles());
        return [
            'email' => $user->getEmail(),
            'id' => $user->getId(),
            'roles' => $user->getRoles(),
        ];
    }

    public function generateToken(User $user): string
    {
        $userData = $this->loadUserData($user);
        $algorithmManager = new AlgorithmManager([
            new HS256(),
        ]);
        $jwk = new JWK([
            'kty' => 'oct',
            'k' => $_ENV['JWT_SECRET_KEY'],
        ]);

        $jwsBuilder = new JWSBuilder($algorithmManager);
        $payload = json_encode([
            'iat' => time(),
            'nbf' => time(),
            'exp' => time() + 3600,
            'email' => $userData['email'],
            'id' => $userData['id'],
            'roles' => $userData['roles'],
        ]);
//dd( $payload);
        $jws = $jwsBuilder
            ->create()
            ->withPayload($payload)
            ->addSignature($jwk, ['alg' => 'HS256'])
            ->build();

        $serializer = new CompactSerializer();

        return $serializer->serialize($jws, 0);
    }

    public function validateToken($request): array
    {
        $authorizationHeader = $request->headers->get('Authorization');
        if (null === $authorizationHeader) {
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }
        $apiToken = str_replace('Bearer ', '', $authorizationHeader);
        $algorithmManager = new AlgorithmManager([
            new HS256(),
        ]);
        $jwsVerifier = new JWSVerifier($algorithmManager);
        $jwk = new JWK([
            'kty' => 'oct',
            'k' => $_ENV['JWT_SECRET_KEY'],
        ]);

        $serializerManager = new JWSSerializerManager([
            new CompactSerializer(),
        ]);

        try {
            $jws = $serializerManager->unserialize($apiToken);
        } catch (\InvalidArgumentException $e) {
            throw new CustomUserMessageAuthenticationException('Invalid token');
        }
        $headerCheckerManager = new HeaderCheckerManager(
            [
                new AlgorithmChecker(['HS256']),
            ],
            [
                new JWSTokenSupport(), // Adds JWS token type support
            ]
        );
        try {
            $headerCheckerManager->check($jws, 0);
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }

        if (!$jwsVerifier->verifyWithKey($jws, $jwk, 0)) {
            throw new CustomUserMessageAuthenticationException('Invalid token signature');
        }
        $payload = json_decode($jws->getPayload(), true);

//        dd($payload,$payload[0]['email']);
        if ($payload['exp'] < time()) {
            throw new CustomUserMessageAuthenticationException('Token has expired ! please Login.');
        }

        if (!isset($payload['email'])) {
            throw new CustomUserMessageAuthenticationException('Token is missing the payload email');
        }
//        dd('here');
        if (!isset($payload['id'])) {
            throw new CustomUserMessageAuthenticationException('Token is missing the payload id');
        }
        return $payload;
    }

}
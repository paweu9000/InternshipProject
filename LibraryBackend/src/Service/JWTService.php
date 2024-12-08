<?php
namespace App\Service;

use App\Exception\UnauthorizedRequestException;
use App\Security\EndUser;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class JWTService
{
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(JWTTokenManagerInterface $jWTManager) {
        $this->jwtManager = $jWTManager;
    }

    public function generateToken(): string {
        $endUser = new EndUser();
        $payload = [
            'iat' => time(),
            'exp' => time() + 3600,
            'username' => $endUser->getUsername()
        ];
        
        return $this->jwtManager->createFromPayload($endUser, $payload);
    }

    public function verifyToken(Request $request): void {
        $authHeader = $request->headers->get('Authorization');

        if (!$authHeader || !preg_match('/^Bearer\s(\S+)/', $authHeader, $matches)) {
            throw new UnauthorizedRequestException();
        }

        $token = $matches[1];

        $decodedToken = $this->jwtManager->parse($token);

        if (isset($decodedToken['exp']) && $decodedToken['exp'] < time()) {
            throw new UnauthorizedRequestException('Token expired, refresh page');
        }

        if (isset($decodedToken['username']) && $decodedToken['username'] === 'SYSTEM') {
            return;
        } else {
            throw new UnauthorizedRequestException();
        }
    }
}
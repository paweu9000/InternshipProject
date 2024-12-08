<?php
namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class EndUser implements UserInterface
{
    private string $username;
    private array $roles;

    public function __construct()
    {
        $this->username = 'SYSTEM';
        $this->roles = ['ROLE_USER'];
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getPassword(): string
    {
        return '';
    }

    public function getSalt()
    {
        return null; // No salt needed for dummy user
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }
}
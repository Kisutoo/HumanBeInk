<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

final readonly class OAuthRegistrationService
{
    public function persist(ResourceOwnerInterface $resourceOwner, UserRepository $repository): User
    {
        $role = ["ROLE_USER"];

        $user = (new User())
            ->setPseudonyme($resourceOwner->getFirstName())
            ->setEmail($resourceOwner->getEmail())
            ->setGoogleId($resourceOwner->getId())
            ->setRoles($role);

        $repository->add($user, true);

        return $repository->findOneBy(['googleId' => $user->getGoogleId()]);
    }
}
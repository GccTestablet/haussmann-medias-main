<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use App\Service\Security\SecurityManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    final public const SAME_USER = 'same_user';

    public function __construct(
        private readonly SecurityManager $securityManager
    ) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject instanceof User) {
            return false;
        }

        return $attribute === self::SAME_USER;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();

        if (!$currentUser instanceof User) {
            return false;
        }

        if (!$subject instanceof User) {
            return false;
        }

        $isSuperAdmin = $this->securityManager->hasRole(User::ROLE_SUPER_ADMIN);

        return match ($attribute) {
            self::SAME_USER => $currentUser === $subject || $isSuperAdmin,
            default => false,
        };
    }
}

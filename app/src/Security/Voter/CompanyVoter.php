<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Company;
use App\Entity\User;
use App\Enum\UserCompanyPermissionEnum;
use App\Service\Company\CompanyUserAccessManager;
use App\Service\Security\SecurityManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CompanyVoter extends Voter
{
    final public const COMPANY_ADMIN = 'company_admin';

    public function __construct(
        private readonly SecurityManager $securityManager,
        private readonly CompanyUserAccessManager $companyUserAccessManager
    ) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!$subject instanceof Company) {
            return false;
        }

        return $attribute === self::COMPANY_ADMIN;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();

        if (!$currentUser instanceof User) {
            return false;
        }

        if (!$subject instanceof Company) {
            return false;
        }

        $isSuperAdmin = $this->securityManager->hasRole(User::ROLE_SUPER_ADMIN);

        return match ($attribute) {
            self::COMPANY_ADMIN => $this->companyUserAccessManager->hasAccess($subject, $currentUser, UserCompanyPermissionEnum::ADMIN) || $isSuperAdmin,
            default => false,
        };
    }
}

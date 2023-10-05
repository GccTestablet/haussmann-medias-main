<?php

declare(strict_types=1);

namespace App\Twig\Component\Layout;

use App\Entity\Company;
use App\Entity\User;
use App\Service\Company\CompanyManager;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'layout_user_company_selector', template: 'component/layout/user_company_selector.html.twig')]
class UserCompanySelectorComponent
{
    public User $user;

    public function __construct(
        private readonly CompanyManager $companyManager
    ) {}

    /**
     * @return Company[]
     */
    public function getCompanies(): array
    {
        return $this->companyManager->findByUser($this->user);
    }
}

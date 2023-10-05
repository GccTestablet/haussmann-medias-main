<?php

declare(strict_types=1);

namespace App\Twig\Runtime;

use App\Entity\User;
use App\Tools\Parser\DateParser;
use DateTime;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\RuntimeExtensionInterface;
use function sprintf;

class FormatExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator
    ) {}

    public function formatDate(?DateTime $dateTime): ?string
    {
        return $dateTime?->format(DateParser::FR_DATE_FORMAT);
    }

    public function formatDateTime(?DateTime $dateTime): ?string
    {
        return $dateTime?->format(DateParser::FR_DATETIME_FORMAT);
    }

    public function formatUser(?User $user): ?string
    {
        if (!$user) {
            return null;
        }

        return sprintf(
            '<a href="%s">%s</a>',
            $this->urlGenerator->generate('app_user_show', ['id' => $user->getId()]),
            $user->getFullName()
        );
    }
}

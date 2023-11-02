<?php

declare(strict_types=1);

namespace App\Twig\Runtime;

use App\Entity\User;
use App\Tools\Parser\DateParser;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\RuntimeExtensionInterface;

class FormatExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly TranslatorInterface $translator,
    ) {}

    public function formatDate(?\DateTime $dateTime, bool $unlimited = false): ?string
    {
        if (!$dateTime && $unlimited) {
            return '<i class="fas fa-infinity"></i>';
        }

        return $dateTime?->format(DateParser::FR_DATE_FORMAT);
    }

    public function formatDateTime(?\DateTime $dateTime, bool $unlimited = false): ?string
    {
        if (!$dateTime && $unlimited) {
            return '<i class="fas fa-infinity"></i>';
        }

        return $dateTime?->format(DateParser::FR_DATETIME_FORMAT);
    }

    public function formatInitial(string $text): ?string
    {
        $words = \explode(' ', $text);

        if (\count($words) === 1) {
            return \mb_substr($words[0], 0, 1);
        }

        return \mb_substr($words[0], 0, 1).\mb_substr($words[1], 0, 1);
    }

    public function formatUser(?User $user): ?string
    {
        if (!$user) {
            return null;
        }

        return \sprintf(
            '<a href="%s">%s</a>',
            $this->urlGenerator->generate('app_user_show', ['id' => $user->getId()]),
            $user->getFullName()
        );
    }

    public function formatCountry(?string $countryCode): ?string
    {
        if (!$countryCode) {
            return null;
        }

        return Countries::getName($countryCode);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function formatAttributes(array $attributes): string
    {
        $result = [];

        foreach ($attributes as $key => $value) {
            if ($value instanceof TranslatableMessage) {
                $value = $this->translator->trans($value->getMessage(), $value->getParameters(), $value->getDomain());
            }

            $result[] = \sprintf('%s="%s"', $key, $value);
        }

        return \implode(' ', $result);
    }

    public function boolFormat(mixed $value, bool $onlyTrue = false, bool $color = false): ?string
    {
        $value = (bool) $value;

        if ($value) {
            return \sprintf("<i class='fas fa-check %s'></i>", $color ? 'text-success' : null);
        }

        if ($onlyTrue) {
            return null;
        }

        return \sprintf("<i class='fas fa-times %s'></i>", $color ? 'text-danger' : null);
    }

    public function archivedFormat(bool $isArchived): ?string
    {
        if (!$isArchived) {
            return null;
        }

        return \sprintf(
            "<i class='fas fa-archive text-warning' title='%s'></i>",
            $this->translator->trans('Archived', [], 'misc'),
        );
    }
}

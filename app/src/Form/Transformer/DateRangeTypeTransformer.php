<?php

declare(strict_types=1);

namespace App\Form\Transformer;

use App\Form\Dto\Common\DateRangeFormDto;
use App\Tools\Parser\DateParser;
use Symfony\Component\Form\DataTransformerInterface;

class DateRangeTypeTransformer implements DataTransformerInterface
{
    public function __construct(
        private readonly DateParser $dateParser,
    ) {}

    public function transform(mixed $value): string
    {
        if (null === $value) {
            return '';
        }

        return \sprintf(
            '%s - %s',
            $value->getFrom()?->format(DateParser::FR_DATE_FORMAT),
            $value->getTo()?->format(DateParser::FR_DATE_FORMAT),
        );
    }

    public function reverseTransform(mixed $value): ?DateRangeFormDto
    {
        if (null === $value) {
            return null;
        }

        [$from, $to] = \explode(' - ', (string) $value);

        return new DateRangeFormDto(
            $this->dateParser->createFromMixed($from),
            $this->dateParser->createFromMixed($to)
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Form\Dto\Common;

class DateRangeFormDto
{
    public function __construct(
        private readonly ?\DateTime $from,
        private readonly ?\DateTime $to,
    ) {}

    public function getFrom(): ?\DateTime
    {
        return $this->from;
    }

    public function getTo(): ?\DateTime
    {
        return $this->to;
    }
}

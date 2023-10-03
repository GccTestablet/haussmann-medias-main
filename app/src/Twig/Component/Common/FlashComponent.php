<?php

declare(strict_types=1);

namespace App\Twig\Component\Common;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'flash', template: 'component/common/flash.html.twig')]
class FlashComponent
{
    /**
     * @var array<string, array<string>>
     */
    public array $flashes = [];
}

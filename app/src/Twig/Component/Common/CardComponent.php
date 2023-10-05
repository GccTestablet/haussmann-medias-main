<?php

declare(strict_types=1);

namespace App\Twig\Component\Common;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'card', template: 'component/common/card.html.twig')]
class CardComponent
{
}

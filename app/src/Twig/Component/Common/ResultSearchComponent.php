<?php

declare(strict_types=1);

namespace App\Twig\Component\Common;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'result_search', template: 'component/common/result_search.html.twig')]
class ResultSearchComponent
{
}

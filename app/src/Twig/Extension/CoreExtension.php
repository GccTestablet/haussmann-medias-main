<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Twig\Runtime\CoreExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CoreExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('call_static', [CoreExtensionRuntime::class, 'callStatic']),
        ];
    }
}

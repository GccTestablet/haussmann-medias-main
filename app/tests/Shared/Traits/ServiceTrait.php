<?php

declare(strict_types=1);

namespace App\Tests\Shared\Traits;

trait ServiceTrait
{
    protected function getService(string $id): ?object
    {
        return static::getContainer()->get($id);
    }
}

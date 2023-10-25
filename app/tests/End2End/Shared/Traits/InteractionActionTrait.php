<?php

declare(strict_types=1);

namespace App\Tests\End2End\Shared\Traits;

trait InteractionActionTrait
{
    protected function iClickOn(string $link): void
    {
        $this->client->clickLink($link);
    }

    protected function iClickOnElement(string $selector): void
    {
        $this->client->getMouse()->clickTo($selector);
    }
}

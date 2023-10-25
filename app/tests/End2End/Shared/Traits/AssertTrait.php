<?php

declare(strict_types=1);

namespace App\Tests\End2End\Shared\Traits;

trait AssertTrait
{
    protected function assertPageContains(string $expected): void
    {
        $this->assertElementContains($expected, 'body');
    }

    protected function assertPageNotContains(string $expected): void
    {
        $this->assertElementNotContains($expected, 'body');
    }

    protected function assertMenuContains(string $expected): void
    {
        $this->assertElementContains($expected, '.nav-main');
    }

    protected function assertMenuNotContains(string $expected): void
    {
        $this->assertElementNotContains($expected, '.nav-main');
    }

    protected function assertElementContains(string $expected, string $selector): void
    {
        $this->assertStringContainsString($expected, $this->crawler->filter($selector)->text());
    }

    protected function assertElementNotContains(string $expected, string $selector): void
    {
        $this->assertStringNotContainsStringIgnoringCase($expected, $this->crawler->filter($selector)->text());
    }

    protected function assertUrl(string $expected): void
    {
        $this->assertStringContainsString($expected, $this->client->getCurrentURL());
    }
}

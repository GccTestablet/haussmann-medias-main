<?php

declare(strict_types=1);

namespace App\Tests\End2End\Shared\Traits;

trait AssertActionTrait
{
    protected function iClickOn(string $link): void
    {
        $this->client->clickLink($link);
    }

    protected function iClickOnElement(string $selector): void
    {
        //        $link = $this
        //            ->crawler
        //            ->filter($selector)
        //            ->link()
        //        ;

        $this->client->getMouse()->clickTo($selector);
    }
}

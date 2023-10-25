<?php

declare(strict_types=1);

namespace App\Tests\End2End\Shared\Traits;

use Symfony\Component\Panther\DomCrawler\Crawler;

trait UserTrait
{
    public function iSwitchToCompany(string $companyName): void
    {
        $this->iClickOnElement('.nav-main .dropdown-toggle');
        $this->crawler->filter('.nav-main .dropdown-menu')->each(function (Crawler $node) use ($companyName): void {
            if ($node->text() !== $companyName) {
                return;
            }

            $node->filter('a')->click();
        });

        $this->refreshCrawler();
    }
}

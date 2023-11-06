<?php

declare(strict_types=1);

namespace App\Tests\Behat\Traits;

use PHPUnit\Framework\Assert;

trait FileTrait
{
    /**
     * @Then /^the file "([^"]*)" should be downloaded$/
     */
    public function fileShouldBeDownload(string $fileName): void
    {
        Assert::assertStringContainsString(
            $fileName,
            $this->getSession()->getResponseHeader('Content-Disposition')
        );
    }

    /**
     * @Then /^the downloaded file should contains$/
     */
    public function downloadedFileShouldContains(string $text): void
    {
        $content = $this->getSession()->getPage()->getContent();

        Assert::assertSame($text, $content);
    }
}

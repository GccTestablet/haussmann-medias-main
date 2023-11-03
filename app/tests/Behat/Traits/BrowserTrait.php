<?php

declare(strict_types=1);

namespace App\Tests\Behat\Traits;

trait BrowserTrait
{
    /**
     * @Given /^I click on "([^"]*)"$/
     */
    public function iClickOn(string $selector): void
    {
        $element = $this->getSession()->getPage()->find('css', $selector);

        $element->click();
    }

    /**
     * @Given /^I wait for (\d+) seconds?$/
     */
    public function iWaitFor(int $seconds): void
    {
        $this->getSession()->wait($seconds * 1000);
    }

    /**
     * @Then /^Debug: HTML content$/
     */
    public function iShowHtmlContent(): void
    {
        // @phpstan-ignore-next-line
        echo $this->getSession()->getPage()->getText();
    }
}

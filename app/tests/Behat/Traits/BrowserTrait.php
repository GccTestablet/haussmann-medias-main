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
}

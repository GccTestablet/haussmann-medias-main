<?php

declare(strict_types=1);

namespace App\Tests\Behat\Traits;

trait DebugTrait
{
    /**
     * @Then /^I show HTML$/
     */
    public function iShowHTML(): void
    {
        echo $this->getSession()->getPage()->getText();
    }
}

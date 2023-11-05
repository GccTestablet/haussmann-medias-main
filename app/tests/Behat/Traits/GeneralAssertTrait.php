<?php

declare(strict_types=1);

namespace App\Tests\Behat\Traits;

trait GeneralAssertTrait
{
    /**
     * @Then /^I should see "([^"]*)" (\d+) times$/
     */
    public function iShouldSeeTextTimes(string $text, int $times): void
    {
        $actual = $this->getSession()->getPage()->getText();
        $actual = \preg_replace('/\s+/u', ' ', (string) $actual);
        $regex = '/'.\preg_quote($text, '/').'/ui';

        $found = \preg_match_all($regex, $actual);
        if ($times !== \preg_match_all($regex, $actual)) {
            throw new \RuntimeException(
                \sprintf('"%s" found %d times, expected %d times', $text, $found, $times)
            );
        }
    }
}

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
     * @Given /^I wait to see "(?P<text>(?:[^"]|\\")*)"$/
     */
    public function iWaitToSee(string $text): void
    {
        $text = $this->fixStepArgument($text);

        $this->waitForCallable(
            function () use ($text) {
                $page = $this->getSession()->getPage();
                $actualContent = $this->filterContent($page->getContent());
                $actualText = $this->filterContent($page->getText());
                $regex = $this->prepareRegex($this->fixStepArgument($text));

                return \preg_match($regex, $actualContent) || \preg_match($regex, $actualText);
            }
        );
    }

    /**
     * Poll a callable for $maxSeconds - if we get a non-empty() return, we return it.
     *
     * @throws \RuntimeException If timeout was reached
     */
    private function waitForCallable(callable $callback, int $maxSeconds = 10, string $debugMessage = ''): mixed
    {
        $spinDelaySeconds = 0.1;
        $spinLimit = (int) \ceil($maxSeconds / $spinDelaySeconds);

        for ($i = 0; $i < $spinLimit; ++$i) {
            $payload = $callback();
            if (!empty($payload)) {
                return $payload;
            }
            \usleep((int) ($spinDelaySeconds * 1_000_000));
        }
        throw new \RuntimeException(
            \sprintf(
                'Timeout reached (%f seconds)! %s',
                \round($maxSeconds, 1),
                $debugMessage
            )
        );
    }

    private function filterContent(string $content): string
    {
        return \preg_replace('/\s+/u', ' ', $content);
    }

    private function prepareRegex(string $text): string
    {
        return '/'.\preg_quote($text, '/').'/ui';
    }
}

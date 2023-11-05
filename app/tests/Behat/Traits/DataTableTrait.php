<?php

declare(strict_types=1);

namespace App\Tests\Behat\Traits;

use App\Tools\Parser\ArrayParser;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Element\NodeElement;

trait DataTableTrait
{
    /**
     * @Then /^I should see a table with:$/
     * @Then /^I should see a table "(?P<css>(?:[^"]|\\")*)" with:$/
     */
    public function iShouldSeeTableWith(TableNode $tableNode, string $css = 'table'): void
    {
        $this->checkIfTableIsAvailableOrNot($tableNode, $css, true);
    }

    /**
     * @Then /^I should not see a table with:$/
     * @Then /^I should not see a table "(?P<css>(?:[^"]|\\")*)" with:$/
     */
    public function iShouldNotSeeTableWith(TableNode $tableNode, string $css = 'table'): void
    {
        $this->checkIfTableIsAvailableOrNot($tableNode, $css, false);
    }

    /**
     * @When /^I click "([^"]*)" on the row containing "([^"]*)"$/
     */
    public function iClickOnOnTheRowContaining(string $linkName, string $rowText): void
    {
        $row = $this->getSession()->getPage()->find('css', \sprintf('table tr:contains("%s")', $rowText));
        if (!$row) {
            throw new \LogicException(\sprintf('Cannot find any row on the page containing the text "%s"', $rowText));
        }

        $row->clickLink($linkName);
    }

    private function checkIfTableIsAvailableOrNot(TableNode $tableNode, string $css, bool $isAvailable): void
    {
        $expectedArray = $tableNode->getColumnsHash();
        $table = $this->getSession()->getPage()->find('css', $css);

        if (!$table) {
            throw new \Exception(\sprintf(
                'No table found with selector "%s":',
                $css
            ));
        }

        $headers = $this->findTableHeaders($table);
        $currentArray = $this->findTableColumns($table, $headers);
        $foundRows = [];

        foreach ($expectedArray as $row) {
            $found = false;
            foreach ($currentArray as $currentRow) {
                if (ArrayParser::isSameArrayExcludingDifferentKeys($row, $currentRow)) {
                    $found = true;

                    continue;
                }

                $foundRows[] = \implode(', ', $currentRow);
            }

            if ($found && !$isAvailable) {
                throw new \Exception(\sprintf(
                    'Row "%s" was found but should not',
                    \implode(', ', $row)
                ));
            }

            if (!$found && $isAvailable) {
                throw new \Exception(\sprintf(
                    'Row "%s" was not found.'.\PHP_EOL.\PHP_EOL.'Rows found:'.\PHP_EOL.'%s',
                    \implode(', ', $row),
                    \implode(\PHP_EOL, $foundRows)
                ));
            }
        }
    }

    /**
     * @return array<array<string>>
     */
    private function findTableHeaders(NodeElement $table): array
    {
        $mergedHeaders = [];
        foreach ($table->findAll('css', 'thead tr:not(.behat--excluded)') as $tableRow) {
            $headers = [];
            foreach ($tableRow->findAll('css', 'th,td') as $tableColumn) {
                $headers[] = $this->normaliseDataToCompare($tableColumn);
            }

            $mergedHeaders[] = $headers;
        }

        return $mergedHeaders;
    }

    /**
     * @param array<array<string>> $headers
     *
     * @return array<array<string>>
     */
    private function findTableColumns(NodeElement $table, array $headers): array
    {
        $mergedColumns = [];
        foreach ($table->findAll('css', 'tbody') as $i => $tableBody) {
            foreach ($tableBody->findAll('css', 'tr:not(.behat--excluded)') as $tableRow) {
                $columns = [];
                foreach ($tableRow->findAll('css', 'th,td') as $tableColumn) {
                    $columns[] = $this->normaliseDataToCompare($tableColumn);
                }

                $mergedColumns[] = \array_combine($headers[$i], $columns);
            }
        }

        return $mergedColumns;
    }

    /**
     * Normalises the content of a column to human & easy comparable version
     */
    private function normaliseDataToCompare(NodeElement $element): string
    {
        if ($element->hasAttribute('data-title')) {
            return \trim(\strip_tags($element->getAttribute('data-title')));
        }

        $content = $element->getHtml();
        // replace multiple space/new lines with single space
        $content = \str_replace(['&nbsp;', "\u{a0}", "\u{202f}"], ' ', $content);
        $content = \preg_replace('/\s+/', ' ', $content);

        if (\str_contains($content, 'fas fa-check')) {
            return 'true';
        }
        if (\str_contains($content, 'fa-ban') || \str_contains($content, 'fa-times')) {
            return 'false';
        }

        return \trim(\strip_tags($content));
    }
}

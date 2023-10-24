<?php

declare(strict_types=1);

namespace App\Tests\End2End\Shared\Traits;

use App\Tools\Parser\ArrayParser;
use Symfony\Component\Panther\DomCrawler\Crawler;

trait AssertTableTrait
{
    /**
     * @param array<string> $headers
     * @param array<array<string>> $rows
     */
    protected function assertTableContains(string $selector, array $headers, array $rows, bool $shouldContain = true): void
    {
        $expectedRows = $this->expectedRows($headers, $rows);
        $table = $this->crawler->filter($selector);
        $headers = $this->findTableHeaders($table);
        $currentColumns = $this->findTableColumns($table, $headers);

        $foundRows = [];
        foreach ($expectedRows as $row) {
            $found = false;
            foreach ($currentColumns as $currentRow) {
                if (ArrayParser::isSameArrayExcludingDifferentKeys($row, $currentRow)) {
                    $found = true;

                    continue;
                }

                $foundRows[] = \implode(', ', $currentRow);
            }

            if ($found && !$shouldContain) {
                throw new \Exception(\sprintf(
                    'Row "%s" was found but should not',
                    \implode(', ', $row)
                ));
            }

            if (!$found && $shouldContain) {
                throw new \Exception(\sprintf(
                    'Row "%s" was not found.'.\PHP_EOL.\PHP_EOL.'Rows found:'.\PHP_EOL.'%s',
                    \implode(', ', $row),
                    \implode(\PHP_EOL, $foundRows)
                ));
            }
        }
    }

    /**
     * @return array<string>
     */
    private function findTableHeaders(Crawler $table): array
    {
        $headers = [];
        $table->filter('thead tr th')->each(function (Crawler $th, int $i) use (&$headers): void {
            $headers[$i] = $th->text();
        });

        return $headers;
    }

    /**
     * @param array<string> $headers
     *
     * @return array<array<string, string>>
     */
    public function findTableColumns(Crawler $table, array $headers): array
    {
        $mergedColumns = [];

        $columns = $table
            ->filter('tbody tr')
            ->each(fn (Crawler $tr) => $tr
                ->filter('td')
                ->each(fn (Crawler $td) => $td->text())
            )
        ;

        foreach ($columns as $column) {
            $mergedColumns[] = \array_combine($headers, $column);
        }

        return $mergedColumns;
    }

    /**
     * @param array<string> $headers
     * @param array<array<string>> $rows
     *
     * @return array<array<string, string>>
     */
    private function expectedRows(array $headers, array $rows): array
    {
        $expectedColumns = [];
        foreach ($rows as $row) {
            $expectedColumns[] = \array_combine(\array_values($headers), $row);
        }

        return $expectedColumns;
    }
}

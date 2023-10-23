<?php

declare(strict_types=1);

namespace App\Tools\Parser;

use League\Csv\Info;
use League\Csv\Reader;
use League\Csv\Writer;

class CsvParser
{
    final public const DELIMITER = ';';

    public function write(string $path): Writer
    {
        $writer = Writer::createFromPath($path);
        $writer->setDelimiter(self::DELIMITER);

        return $writer;
    }

    public function read(string $path): Reader
    {
        $csvReader = Reader::createFromPath($path);
        $csvReader->setDelimiter($this->detectDelimiter($csvReader));
        $csvReader->setHeaderOffset(0);

        return $csvReader;
    }

    private function detectDelimiter(Reader $csvReader): string
    {
        $stats = Info::getDelimiterStats($csvReader, [',', ';', "\t"], 10);

        return \array_reduce(
            \array_keys($stats),
            static fn (string $carry, string $delimiter) => $stats[$carry] > $stats[$delimiter] ? $carry : $delimiter,
            ','
        );
    }
}

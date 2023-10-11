<?php

declare(strict_types=1);

namespace App\Tools\Parser;

use League\Csv\Writer;

class CsvParser
{
    final public const DELIMITER = ';';

    public function write(string $fileName): Writer
    {
        $writer = Writer::createFromPath($fileName);
        $writer->setDelimiter(self::DELIMITER);

        return $writer;
    }
}

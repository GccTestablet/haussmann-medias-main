<?php
//src/Model/Layout/SearchBarResult.php
declare(strict_types=1);

namespace App\Model\Layout\SearchBarResult;

class SearchBarResult
{
    public function __construct(private string $label) {
    }

    public function getLabel(): string {
        return $this->label;
    }
}

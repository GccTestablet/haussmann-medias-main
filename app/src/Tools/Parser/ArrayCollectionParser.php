<?php

declare(strict_types=1);

namespace App\Tools\Parser;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ArrayCollectionParser
{
    public static function sort(Collection $collection, \Closure $callBack): Collection
    {
        $array = $collection->toArray();
        \usort($array, $callBack);

        return new ArrayCollection($array);
    }
}

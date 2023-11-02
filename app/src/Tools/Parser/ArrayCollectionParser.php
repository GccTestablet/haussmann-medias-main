<?php

declare(strict_types=1);

namespace App\Tools\Parser;

use Doctrine\Common\Collections\ArrayCollection;

class ArrayCollectionParser
{
    public static function sort(ArrayCollection $collection, \Closure $callBack): ArrayCollection
    {
        $array = $collection->toArray();
        \usort($array, $callBack);

        return new ArrayCollection($array);
    }
}

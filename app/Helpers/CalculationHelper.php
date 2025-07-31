<?php

namespace App\Helpers;
use Illuminate\Support\Collection;
use Symfony\Component\Routing\Matcher\Dumper\StaticPrefixCollection;
class CalculationHelper
{
    public static $methods = [
        'add_collection' => 'addCollections', // Method name stored in config
        'subtract_collection' => 'subtractCollections',
        'multiply_collection' => 'multiplyCollections',
        'divide_collection' => 'divideCollections',
        'percentage_collection' => 'percentageCollections',
        'add_value' => 'addValue',
        'divide_value' => 'divideValue',
        'subtract_value' => 'subtractValue',
    ];

    // public static function decider($collection, $config) {
    //     $method = self::$methods[$config['calculation']];
    //     $params = $collection->filter(function ($value, $key) use ($config) {
    //             return collect($config['calculation_slug'])->contains($key);
    //         })->values();


    //     $values = $params->filter(fn($item) => !($item instanceof Collection));
    //     $collections = $params->filter(fn($item) => $item instanceof Collection);

    //     if($values->isNotEmpty()){
    //         // dd("s");
    //         return self::addValue($values);

    //     }
    //     elseif($collection->isNotEmpty()){
    //         return call_user_func([__CLASS__, $method], $collections->first(), $collections->last());
    //     }

    //     dd($params, $collection, true);
    //     // return self::{self::$methods[$method]}(...$collections);
    // }

    public static function addValue($slug1, $slug2) {
        return $slug1 + $slug2;
    }

    public static function divideValue($slug1, $slug2) {
        return ($slug1 != 0 || $slug2 != 0)  ? $slug1 / $slug2 : null;
    }

    public static function subtractValue($slug1, $slug2) {
        return $slug1 - $slug2;
    }

    public static function addCollections($collection1, $collection2): Collection {
        return $collection1->zip($collection2)->map(function ($pair) {
            return $pair[0] + $pair[1];
        });
    }

    public static function subtractCollections($collection1, $collection2): Collection {
        return $collection1->zip($collection2)->map(function ($pair) {
            return $pair[0] - $pair[1];
        });
    }

    public static function multiplyCollections($collection1, $collection2): Collection {
        return $collection1->zip($collection2)->map(function ($pair) {
            return $pair[0] * $pair[1];
        });
    }

    public static function divideCollections($collection1, $collection2): Collection {
        return $collection1->zip($collection2)->map(function ($pair) {
            return $pair[1] != 0 ? $pair[0] / $pair[1] : null; // Handle division by zero
        });
    }

    public static function percentageCollections(Collection $numerator, Collection $denomator): Collection {
        return $numerator->zip($denomator)->map(function ($pair) {
            return $pair[1] != 0 ? ($pair[0] / $pair[1]) * 100 : null; // Handle division by zero
        });
    }
}


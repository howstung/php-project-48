<?php

namespace Differ\Differ;

use function Differ\Parsers\parseFileToArray;
use function Differ\Formatters\Format;
use function Functional\sort as sort;

function genDiff(string $path1, string $path2, string $format = 'stylish'): string
{
    $array1 = parseFileToArray($path1);
    $array2 = parseFileToArray($path2);

    $diff = buildDiff($array1, $array2);

    return Format($diff, $format);
}


function buildDiff(array $array1, array $array2): array
{
    $arrayCommonKeys = array_unique(array_merge(array_keys($array1), array_keys($array2)));
    $sortedKeys = array_values(sort($arrayCommonKeys, fn($left, $right) => strcmp($left, $right), true));

    return array_map(
        function ($key) use ($array1, $array2) {
            if (
                array_key_exists($key, $array1) && array_key_exists($key, $array2) && $array1[$key] === $array2[$key]
            ) {
                return makeNode('equals', $key, $array1);
            } elseif (array_key_exists($key, $array1) && array_key_exists($key, $array2)) {
                if (is_array($array1[$key]) && is_array($array2[$key])) {
                    return [
                        ...makeNode('equals_root', $key, $array1, $array2),
                        'children' => buildDiff($array1[$key], $array2[$key])
                    ];
                } else {
                    return makeNode('changed', $key, $array1, $array2);
                }
            } elseif (array_key_exists($key, $array1)) {
                return makeNode('minus', $key, $array1);
            } elseif (array_key_exists($key, $array2)) {
                return makeNode('plus', $key, $array2);
            }
        },
        $sortedKeys
    );
}


function makeNode(string $status, string $key, mixed ...$arrays): array
{
    $baseNode = [
        'status' => $status,
        'key' => $key,
        'value' => $arrays[0][$key],
    ];
    if (!in_array($status, ['plus', 'minus', 'equals'], true)) {
        return [
            ...$baseNode,
            'value2' => count($arrays[1]) === 0 ? null : $arrays[1][$key],
        ];
    }
    return $baseNode;
}

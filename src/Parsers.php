<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

use function Functional\sort as sort;

function parseFileToArray(string $path): array
{
    $fileExtension = getExtensionFile($path);
    if (in_array($fileExtension, ['yml', 'yaml'], true)) {
        $jsonData = getDataFromYMLFile($path);
        return toSortedArrayByKeys($jsonData);
    } else {
        // default: json
        $ymlData = getDataFromJSONile($path);
        return toSortedArrayByKeys($ymlData);
    }
}

function getExtensionFile(string $path)
{
    return substr($path, ((int)strripos($path, '.') + 1));
}

function getDataFromJSONile(string $path): array
{
    return (array)json_decode((string)file_get_contents($path));
}

function getDataFromYMLFile(string $path): array
{
    return (array)Yaml::parseFile($path, Yaml::PARSE_OBJECT_FOR_MAP);
}


function toSortedArrayByKeys(mixed $array): array
{
    $keys = getSortedKeys((array)$array);
    $templateArr = array_fill_keys($keys, []);
    $values = array_map(
        function ($t, $key) use ($array) {
            if (is_object($array[$key]) || is_array($array[$key])) {
                return toSortedArrayByKeys((array)$array[$key]);
            } else {
                return $array[$key];
            }
        },
        $templateArr,
        $keys
    );
    return array_combine($keys, $values);
}

function getSortedKeys(array $array1): array
{
    return array_values(sort(array_keys($array1), fn($left, $right) => strcmp($left, $right), true));
}

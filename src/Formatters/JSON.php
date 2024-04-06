<?php

namespace Differ\Formatters\JSON;

use function Differ\Utils\hasChildren;
use function Differ\Utils\getStatus;
use function Differ\Utils\getKey;
use function Differ\Utils\getValue;
use function Differ\Utils\getChilldren;
use function Differ\Utils\normalizeValue;
use function Differ\Utils\formatValue;

const SPACES_CNT = 2;

function formatJSON(array $diff): string
{
    $JSON = array_reduce($diff, function ($acc, $child) {
        $key = getKey($child);
        $newNode = makeNode($child);
        return [...$acc, $key => $newNode];
    }, []);

    return toJSON($JSON);
}

function makeLeaf(array $node): mixed
{
    $value = getValue($node);
    switch (getStatus($node)) {
        case "equals":
            return $value;
        case "minus":
            return ["flag" => 'remove', "old_value" => $value];
        case "changed":
            $value2 = getValue($node, 2);
            return ["flag" => 'update', "old_value" => $value, "new_value" => $value2];
        default: //"plus"
            return ["flag" => 'add', "new_value" => $value];
    }
}

function makeNode(array $node): mixed
{
    if (hasChildren($node)) {
        $children = getChilldren($node);

        $childrenNodes = array_reduce($children, function ($acc, $child) {
            $key = getKey($child);
            $newNode = makeNode($child);
            return [...$acc, $key => $newNode];
        }, []);

        return $childrenNodes;
    }
    return makeLeaf($node);
}

function toJSON(array $node, int $depth = 1): string
{
    $spacesStr = str_repeat(" ", SPACES_CNT * $depth);

    $strArr = array_map(function ($key, $child) use ($depth, $spacesStr, $node) {
        if (is_array($child)) {
            $value = toJSON($child, $depth + 1);
        } else {
            $value = formatValue(normalizeValue($child));
        }

        if (isLastKeyOfArray($key, $node)) {
            return "$spacesStr\"{$key}\": $value";
        }
        return "$spacesStr\"{$key}\": $value,";
    }, array_keys($node), $node);

    $spacesBeforeBrace = str_repeat(" ", SPACES_CNT * ($depth - 1));
    $string = implode("\n", $strArr);
    return "{\n$string\n$spacesBeforeBrace}";
}

function isLastKeyOfArray(string $key, array $array): bool
{
    if ($key === array_keys($array)[count(array_keys($array)) - 1]) {
        return true;
    }
    return false;
}

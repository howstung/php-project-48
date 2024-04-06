<?php

namespace Differ\Formatters\Plain;

use function Differ\Utils\hasChildren;
use function Differ\Utils\getStatus;
use function Differ\Utils\getKey;
use function Differ\Utils\getValue;
use function Differ\Utils\getChilldren;
use function Differ\Utils\normalizeValue;
use function PHPUnit\Framework\isNull;

function formatPlain(array $diff): string
{
    $lines = array_map(function ($child) {
        return find($child, [], "");
    }, $diff);

    return implode("\n", array_merge(...$lines));
}

function find(array $node, array $acc, string $path = ""): array
{
    $newPath = makePath($path, getKey($node));
    $currentStrDiff = getDiffCurrentNode($node, $newPath);
    if (!is_null($currentStrDiff)) {
        $newAcc = [...$acc, $currentStrDiff];
    } else {
        $newAcc = $acc;
    }
    if (hasChildren($node)) {
        $accWithChildrens = array_reduce(getChilldren($node), function ($acc, $node) use ($newPath) {
            return find($node, $acc, $newPath);
        }, $newAcc);
        return $accWithChildrens;
    }
    return $newAcc;
}

function getDiffCurrentNode(array $node, string $path = ""): ?string
{
    if (str_contains((string)getStatus($node), 'equals')) {
        return null;
    }
    $status = getStatus($node);
    $wordsStart = "Property '$path'";
    $valueStr1 = formatValue(normalizeValue(getValue($node)));
    switch ($status) {
        case "minus":
            $wordsFinish = ' was removed';
            break;
        case "changed":
            $valueStr2 = formatValue(normalizeValue(getValue($node, 2)));
            $wordsFinish = " was updated. From $valueStr1 to " . $valueStr2;
            break;
        default:
            //"plus"
            $wordsFinish = " was added with value: $valueStr1";
            break;
    }
    return $wordsStart . $wordsFinish;
}

function formatValue(mixed $valueStrRaw): string
{
    $value = $valueStrRaw;
    if (is_array($valueStrRaw)) {
        return '[complex value]';
    }
    if (is_string($valueStrRaw) === true && !in_array($value, ['null', 'false', 'true', '[complex value]'], true)) {
        return "'$value'";
    }
    return $valueStrRaw;
}


function makePath(string $path, string $key): string
{
    if ($path === "") {
        return $key;
    }
    return "$path.$key";
}

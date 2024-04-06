<?php

namespace Differ\Formatters\Stylish;

use function Differ\Utils\getChilldren;
use function Differ\Utils\getKey;
use function Differ\Utils\getStatus;
use function Differ\Utils\getValue;
use function Differ\Utils\hasChildren;
use function Differ\Utils\normalizeValue;

const SPACES_CNT = 4;

function formatStylish(array $diff): string
{
    $stringDiff = mapStringDiff($diff, 1);
    return "{\n$stringDiff\n}";
}

function mapStringDiff(array $node, int $depth = 1): string
{
    $lineS = array_map(fn($child) => formatLine($child, $depth), $node);
    return implode("\n", $lineS);
}

function formatLine(array $node, int $depth): string
{
    $stringSpaces = getStringSpaces($depth);
    if (hasChildren($node)) {
        $children = getChilldren($node);
        $stringDiff = mapStringDiff($children, $depth + 1);
        $value = "{\n$stringDiff\n$stringSpaces}";
        $lines = [];
    } else {
        if (is_array($node['value'])) {
            $innerValueString = getValueIfArray(getValue($node), $depth, $stringSpaces);
            $newArrayValuesStr = [...$node, 'value' => $innerValueString];
            $lines = [makeLineByStatus($newArrayValuesStr, $depth)];
        } else {
            $lines = [makeLineByStatus($node, $depth)];
        }
        $value = '';
    }
    if (getStatus($node) === "equals_root") {
        $newNode = ['key' => $node['key'], 'value' => $value, 'value2' => $node['value2'], 'status' => ''];
        $linesNew = [...$lines, formatLine($newNode, $depth - 1)];
    } else {
        $linesNew = $lines;
    }
    return implode("\n", $linesNew);
}


function getStringSpaces(int $depth, string $prefixEmpty = ''): string
{
    $cntSpacesDefault = SPACES_CNT * ($depth);
    if ($prefixEmpty == '') {
        $spacesCount = $cntSpacesDefault;
    } else {
        $spacesCount = $cntSpacesDefault - SPACES_CNT;
    }
    return str_repeat(" ", $spacesCount);
}

function makeLineByStatus(array $node, int $depth): string
{
    $key = getKey($node);
    $status = getStatus($node);
    switch ($status) {
        case "equals":
            $line = makeLine($key, $node['value'], " ", $depth);
            break;
        case "minus":
            $line = makeLine($key, $node['value'], "-", $depth);
            break;
        case "plus":
            $line = makeLine($key, $node['value'], "+", $depth);
            break;
        case "changed":
            $lines = [
                makeLine($key, $node['value'], "-", $depth),
                makeLine($key, $node['value2'], "+", $depth)
            ];
            $line = implode("\n", $lines);
            break;
        default:
            $line = makeLine($key, $node['value'], " ", $depth + 1);
    }
    return $line;
}

function makeLine(string $key, mixed $rawValue, string $pref, int $depth): string
{
    $normValue = normalizeValue($rawValue);
    if (is_array($normValue)) {
        $stringSpaces = getStringSpaces($depth + 1, $pref);
        $value = getValueIfArray($normValue, $depth, $stringSpaces);
    } else {
        $value = $normValue;
    }
    $stringSpaces = getStringSpaces($depth, $pref);
    return "$stringSpaces  {$pref} {$key}: $value";
}


function getValueIfArray(array $node, int $depth, string $stringSpaces): string
{
    $valueS = array_map(function ($innerKey, $innerValue) use ($depth) {
        return makeLineByStatus(['key' => $innerKey, 'value' => $innerValue], $depth);
    },
        array_keys($node), $node);

    $newValueArr = implode("\n", $valueS);
    return "{\n$newValueArr\n$stringSpaces}";
}

<?php

namespace Differ\Utils;

function hasChildren(array $array): bool
{
    if (array_key_exists('children', $array)) {
        return true;
    } else {
        return false;
    }
}

function getStatus(array $node): ?string
{
    return array_key_exists('status', $node) ? $node['status'] : null;
}

function getKey(array $node): string
{
    return $node['key'];
}

function getValue(array $node, int $second = 1): mixed
{
    if ($second === 2) {
        return $node['value2'];
    }
    return $node['value'];
}

function getChilldren(array $node): mixed
{
    return $node['children'];
}

function normalizeValue(mixed $valueRaw): mixed
{
    if ($valueRaw === true) {
        return 'true';
    } elseif ($valueRaw === false) {
        return 'false';
    } elseif ($valueRaw === null) {
        return 'null';
    }
    return $valueRaw;
}

function formatValue(mixed $valueStrRaw): string
{
    $value = $valueStrRaw;
    if (
        is_string($valueStrRaw) === true &&
        !in_array($valueStrRaw, ['null', 'false', 'true', '[complex value]'], true)
    ) {
        return "\"$value\"";
    }
    return $value;
}

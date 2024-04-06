<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\formatStylish;
use function Differ\Formatters\Plain\formatPlain;
use function Differ\Formatters\JSON\formatJSON;

function Format(array $diff, string $format = 'stylish'): string
{
    if ($format == 'plain') {
        return formatPlain($diff);
    } elseif ($format == 'json') {
        return formatJSON($diff);
    } else {
        return formatStylish($diff);
    }
}

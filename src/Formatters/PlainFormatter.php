<?php

namespace Differ\Formatters\PlainFormatter;

use function Differ\Formatters\PrettyFormatter\boolToString;

function toPlainFormat($diffTree)
{
    return rtrim(toPlain($diffTree, ""));
}
function toPlain($diffTree, $keysAncestors)
{
    $formatted =  array_map(function ($item) use ($keysAncestors) {
        $status = $item['status'];
        if ($status == 'nested') {
            $children = $item['children'];
            $keysAncestors .= empty($keysAncestors) ? "{$item['key']}" : ".{$item['key']}";
            return toPlain($children, $keysAncestors);
        }

        $key = $item['key'];
        $fullKeysPath = empty($keysAncestors) ? $key : $keysAncestors . "." . $key;
        if ($status == 'unchanged') {
            return "";
        }
        if ($status == 'changed') {
            $oldValue = processArrayValueAndWrappring($item['oldValue']);
            $newValue = processArrayValueAndWrappring($item['newValue']);

            return "Property '$fullKeysPath' was updated. From $oldValue to $newValue\n";
        }

        if ($status == 'deleted') {
            return "Property '$fullKeysPath' was removed\n";
        }

        if ($status == 'added') {
            $value = processArrayValueAndWrappring($item['value']);
            return  "Property '$fullKeysPath' was added with value: {$value}\n";
        }

        return "";
    }, $diffTree);

    return implode("", $formatted);
}

function wrappingStrToQuotes($str)
{
    return ($str !== 'true' && $str !== 'false'
        && $str !== '[complex value]' && !is_numeric($str)) ? "'" . $str . "'" : $str;
}

function processArrayValueAndWrappring($value)
{
    $temp = is_array($value) ? '[complex value]' : boolToString($value);
    return wrappingStrToQuotes($temp);
}

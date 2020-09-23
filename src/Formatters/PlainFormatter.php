<?php

namespace Differ\Formatters\PlainFormatter;

function toPlainFormat($diffTree)
{
    return rtrim(toPlain($diffTree, ""));
}
function toPlain($diffTree, $keysAncestors)
{
    $formatted =  array_map(function ($item) use ($keysAncestors) {
        $status = $item['status'];
        $keysPath = empty($keysAncestors) ? "{$item['key']}" : "$keysAncestors.{$item['key']}";

        switch ($status) {
            case 'nested':
                return toPlain($item['children'], $keysPath);

            case 'unchanged':
                return "";

            case 'changed':
                $oldValue = getFormattedValue($item['oldValue']);
                $newValue = getFormattedValue($item['newValue']);

                return "Property '$keysPath' was updated. From $oldValue to $newValue\n";

            case 'deleted':
                return "Property '$keysPath' was removed\n";

            case 'added':
                $value = getFormattedValue($item['value']);

                return "Property '$keysPath' was added with value: {$value}\n";
        }

        return "";
    }, $diffTree);

    return implode("", $formatted);
}

function getFormattedValue($value)
{
    if (is_bool($value)) {
        return ($value) ? 'true' : 'false';
    }

    if (is_numeric($value)) {
        return $value;
    }

    if (is_array($value)) {
        return '[complex value]';
    }

    return "'{$value}'";
}

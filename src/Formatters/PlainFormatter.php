<?php

namespace Differ\Formatters\PlainFormatter;

use function Funct\Collection\flattenAll;

function toPlainFormat($diffTree)
{
    return rtrim(toPlain($diffTree, ""));
}
function toPlain($diffTree, $keysAncestors)
{
    $formattedData =  array_map(function ($item) use ($keysAncestors) {
        $status = $item['status'];
        $keysPath = empty($keysAncestors) ? "{$item['key']}" : "$keysAncestors.{$item['key']}";

        switch ($status) {
            case 'nested':
                return toPlain($item['children'], $keysPath);

            case 'unchanged':
                return [];

            case 'changed':
                $oldValue = getFormattedValue($item['oldValue']);
                $newValue = getFormattedValue($item['newValue']);

                return "Property '$keysPath' was updated. From $oldValue to $newValue";

            case 'deleted':
                return "Property '$keysPath' was removed";

            case 'added':
                $value = getFormattedValue($item['value']);

                return "Property '$keysPath' was added with value: {$value}";

            default:
                throw new \Exception("Status {$status} should not existed in /$/diffTree[/$/outerKey]['status']");
        }
    }, $diffTree);

    return implode("\n", flattenAll($formattedData));
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

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
            $oldValue = getFormattedValue($item['oldValue']);
            $newValue = getFormattedValue($item['newValue']);

            return "Property '$fullKeysPath' was updated. From $oldValue to $newValue\n";
        }
        if ($status == 'deleted') {
            return "Property '$fullKeysPath' was removed\n";
        }
        if ($status == 'added') {
            $value = getFormattedValue($item['value']);
            return  "Property '$fullKeysPath' was added with value: {$value}\n";
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

    return "'" . $value . "'";
}

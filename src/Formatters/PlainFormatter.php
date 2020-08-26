<?php

namespace Differ\Formatters\PlainFormatter;

use function Differ\Differ\boolToString;
use function PHPUnit\Framework\isEmpty;

function toPlainFormat($ast, $keysAncestors = "")
{

    return   array_reduce($ast, function ($output, $item) use ($keysAncestors) {
        $status = $item['status'];
        if ($status == 'nested') {
            $children = $item['children'];
            $keysAncestors .= empty($keysAncestors) ? "{$item['key']}" : ".{$item['key']}";
            $output .= toPlainFormat($children, $keysAncestors);
            return $output;
        } else {
            $output .= printProperty($item, $keysAncestors);
            return  $output;
        }
    }, "");
}


function printProperty($item, $keysAncestors)
{
    $status = $item['status'];
    $key = $item['key'];
    $fullKeysPath = empty($keysAncestors) ? $key : $keysAncestors . "." . $key;
    if ($status == 'unchanged') {
        return  "";
    }
    if ($status == 'changed') {
        $oldValue = is_array($item['oldValue']) ? '[complex value]' : $item['oldValue'];
        $newValue = is_array($item['newValue']) ? '[complex value]' : $item['newValue'];

        $oldValue = wrappingStrToQuotes($oldValue);
        $newValue = wrappingStrToQuotes($newValue);

        $res =  "Property '$fullKeysPath' was updated. From $oldValue to $newValue\n";
    } else {
        $value = $item['value'];
        if ($status == 'deleted') {
            $res = "Property '$fullKeysPath' was removed\n";
        }

        if ($status == 'added') {
            $value = is_array($value) ? '[complex value]' : $value;
            $value = wrappingStrToQuotes($value);
            $res = "Property '$fullKeysPath' was added with value: {$value}\n";
        }
    }
    return  $res;
}

function wrappingStrToQuotes($str)
{
    return ($str !== 'true' && $str !== 'false' && $str !== '[complex value]') ? "'" . $str . "'" : $str;
}

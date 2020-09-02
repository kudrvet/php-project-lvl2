<?php

namespace Differ\Formatters\JsonFormatter;

function toJsonFormat($diffTree)
{
    return "{\n" . rtrim(toJson($diffTree, ""), ",\n") . "\n}";
}

function toJson($ast, $keysAncestors = "")
{

    return   array_reduce($ast, function ($output, $item) use ($keysAncestors) {
        $status = $item['status'];
        if ($status == 'nested') {
            $children = $item['children'];
            $keysAncestors .= empty($keysAncestors) ? "{$item['key']}" : ".{$item['key']}";
            $output .= toJson($children, $keysAncestors);
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
        $oldValue = is_array($item['oldValue']) ? "[complex value]" : $item['oldValue'];
        $newValue = is_array($item['newValue']) ? "[complex value]" : $item['newValue'];

        $oldValue = wrappingStrToQuotes($oldValue);
        $newValue = wrappingStrToQuotes($newValue);


        $res = "  \"{$fullKeysPath}\": {\n    \"status\": \"changed\",\n"
             . "    \"from\": {$oldValue},\n    \"to\": {$newValue}\n  },\n";
    } else {
        $value = $item['value'];
        if ($status == 'deleted') {
            $res = "  \"{$fullKeysPath}\": {\n    \"status\": \"removed\"\n  },\n";
        }

        if ($status == 'added') {
            $value = is_array($value) ? '[complex value]' : $value;
            $value = wrappingStrToQuotes($value);
            $res = "  \"{$fullKeysPath}\": {\n    \"status\": \"added\",\n    \"value\": {$value}\n  },\n";
        }
    }
    return  $res;
}

function wrappingStrToQuotes($str)
{
    return ($str !== 'true' && $str !== 'false' && ! is_numeric($str)) ? '"' . $str . '"' : $str;
}

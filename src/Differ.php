<?php

namespace Differ\Differ;

use function Differ\Formatters\JsonFormatter\toJsonFormat;
use function Differ\Formatters\PrettyFormatter\toPrettyFormat;
use function Differ\Formatters\PlainFormatter\toPlainFormat;
use function Differ\Parsers\YamlParser\toAsoc;
use function Differ\Parsers\JsonParser\toAsoc as jsonToAsoc;

function genDiff($path1, $path2, $format = 'pretty')
{
    $fileBefore = file_get_contents($path1, true);
    $fileAfter = file_get_contents($path2, true);

    $ext = pathinfo($path1, PATHINFO_EXTENSION);

    switch ($ext) {
        case "yaml":
            $beforeAsoc = toAsoc($fileBefore);
            $afterAsoc = toAsoc($fileAfter);
            break;
        case "json":
            $beforeAsoc = jsonToAsoc($fileBefore);
            $afterAsoc = jsonToAsoc($fileAfter);
            break;
    }

    $ast = getDiffAST($beforeAsoc, $afterAsoc);

    switch ($format) {
        case 'pretty':
            return toPrettyFormat($ast);
        case 'plain':
            return toPlainFormat($ast);
        case 'json':
            return toJsonFormat($ast);
        default:
            echo "choose existing formatter!";
    }
}

function getDiffAST(array $beforeAsoc, array $afterAsoc)
{
    $beforeAsoc = array_map(function ($item) {
        return boolToString($item);
    }, $beforeAsoc);

    $afterAsoc = array_map(function ($item) {
        return boolToString($item);
    }, $afterAsoc);

    $diff = array_map(function ($key) use ($afterAsoc, $beforeAsoc) {
        $value = $afterAsoc[$key];
        if (isset($beforeAsoc[$key])) {
            // not changed
            if ($beforeAsoc[$key] === $value) {
                return ['key' => $key, 'value' => $value, 'status' => 'unchanged'];
                // changed
            } elseif ($beforeAsoc[$key] !== $value) {
                if (is_array($beforeAsoc[$key]) && is_array($afterAsoc[$key])) {
                    $childrenBefore = $beforeAsoc[$key];
                    $childrenAfter = $afterAsoc[$key];
                    return ['key' => $key, 'status' => 'nested',
                        'children' => getDiffAST($childrenBefore, $childrenAfter)];
                } else {
                    return ['key' => $key, 'oldValue' => $beforeAsoc[$key],
                        'newValue' => $value, 'status' => 'changed'];
                }
            }
            //add
        } else {
            return  ['key' => $key, 'value' => $value, 'status' => 'added'];
        }
    }, array_keys($afterAsoc));

    $deleted = array_diff_key($beforeAsoc, $afterAsoc);

    $diffDeletedPart = array_map(function ($key) use ($deleted) {
        return ['key' => $key, 'value' => $deleted[$key], 'status' => 'deleted'];
    }, array_keys($deleted));

    return array_merge($diff, $diffDeletedPart);
}

function boolToString($value)
{
    if (is_bool($value)) {
        return ($value) ? 'true' : 'false';
    }
    return $value;
}

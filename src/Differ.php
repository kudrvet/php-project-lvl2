<?php

namespace Differ\Differ;

use function Differ\Formatters\PrettyFormatter\toPrettyFormat;
use function Differ\Formatters\PlainFormatter\toPlainFormat;
use function Differ\Parsers\YamlParser\toAsoc;
use function Differ\Parsers\JsonParser\toAsoc as jsonToAsoc;
use function Funct\Strings\strip;

function boolToString($value)
{
    if (is_bool($value)) {
        return ($value) ? 'true' : 'false';
    }
    return $value;
}

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
        default:
            echo "choose existing formatter!";
    }
}

function getDiffAST(array $beforeAsoc, array $afterAsoc)
{
    $diff = [];

    foreach ($beforeAsoc as $key => $value) {
        $value = boolToString($value);
    }
    foreach ($afterAsoc as $key => $value) {
        if (is_bool($value)) {
            $value = ($value) ? 'true' : 'false';
        }

        if (isset($beforeAsoc[$key])) {
            // not changed
            if ($beforeAsoc[$key] === $value) {
                $diff[] = ['key' => $key, 'value' => $value, 'status' => 'unchanged'];
                // changed
            } elseif ($beforeAsoc[$key] !== $value) {
                if (is_array($beforeAsoc[$key]) && is_array($afterAsoc[$key])) {
                    $childrenBefore = $beforeAsoc[$key];
                    $childrenAfter = $afterAsoc[$key];
                    $diff [] = ['key' => $key, 'status' => 'nested',
                        'children' => getDiffAST($childrenBefore, $childrenAfter)];
                } else {
                    $diff[] = ['key' => $key, 'oldValue' => boolToString($beforeAsoc[$key]),
                        'newValue' => $value, 'status' => 'changed'];
                }
            }
            //add
        } else {
            $diff[] = ['key' => $key, 'value' => $value, 'status' => 'added'];
        }
    }
        //deleted
        $deleted = array_diff_key($beforeAsoc, $afterAsoc);
    foreach ($deleted as $key => $value) {
        $diff[] = ['key' => $key, 'value' => boolToString($value), 'status' => 'deleted'];
    }
    return $diff ;
}

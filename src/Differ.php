<?php

namespace Differ\Differ;

use function Differ\Parsers\YamlParser\toAsoc;
use function  Differ\Parsers\JsonParser\toAsoc as jsonToAsoc;
use function Funct\Strings\strip;

function boolToString($value)
{
    if (is_bool($value)) {
        return ($value) ? 'true' : 'false';
    }
    return $value;
}

function genDiff($path1, $path2)
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
    return toFormat($ast);
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

function toFormat($diffAST)
{
    return "{\n" . toFormatMain($diffAST, $deep = 0) . "}";
}
function toFormatMain($diffAST, $deep)
{
    $formatMap = ['added' => '+ ','deleted' => '- ','unchanged' => '  '];
    $res = "";
    foreach ($diffAST as $key => $value) {
        $status = $diffAST[$key]['status'];
        $keyIn = $diffAST[$key]['key'];
        $tab = str_repeat("    ", $deep);
        $res .= $tab;
        if ($status == 'nested') {
            $children = $diffAST[$key]['children'];
            $res = $res . "    " . $keyIn . ": {\n"
                . toFormatMain($children, $deep + 1)
                . str_repeat("    ", $deep + 1) . "}\n";
        } else {
            $res .= "  ";

            if ($status == 'changed') {
                if (is_array($diffAST[$key]['oldValue'])) {
                    $res = $res . '- ' . $keyIn . ": {"
                        . formatArray($diffAST[$key]['oldValue'], $deep)
                        . str_repeat("    ", $deep + 1) . "}\n";
                } else {
                    $res = $res . '- ' . $keyIn . ': ' . $diffAST[$key]['oldValue'] . "\n";
                }
                // в случае changed нужно добавить еще "  " для смещения newValue;
                $res .= $tab . "  ";
                if (is_array($diffAST[$key]['newValue'])) {
                    $res = $res . '+ ' . $keyIn . ": {"
                        . formatArray($diffAST[$key]['newValue'], $deep)
                        . str_repeat("    ", $deep + 1) . "}\n";
                } else {
                    $res = $res . '+ ' . $keyIn . ': ' . $diffAST[$key]['newValue'] . "\n";
                }
            } else {
                if (is_array($diffAST[$key]['value'])) {
                    $res = $res . $formatMap[$status] . $keyIn . ": {"
                        . formatArray($diffAST[$key]['value'], $deep)
                        . str_repeat("    ", $deep + 1) . "}\n";
                } else {
                    $res = $res . $formatMap[$status] . $keyIn . ': ' . $diffAST[$key]['value'] . "\n";
                }
            }
        }
    }

    return $res;
}

function formatArray($array, $deep)
{
    $stripedStr = strip(json_encode($array, JSON_PRETTY_PRINT), "\"", ",");
    $trimed = trim($stripedStr, "{} ");
    $tab = str_repeat("    ", $deep + 1);
    $res = str_replace("\n    ", "\n" . $tab . "    ", $trimed);

    return $res;
}

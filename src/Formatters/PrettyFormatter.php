<?php

namespace Differ\Formatters\PrettyFormatter;

use function Funct\Strings\strip;

function toPrettyFormat($diffAST)
{
    return "{\n" . mainFormatting($diffAST, $deep = 0) . "}";
}

function mainFormatting($diffAST, $deep)
{
    $formatMap = ['added' => '+ ','deleted' => '- ','unchanged' => '  '];

    $formatted =  array_map(function ($key) use ($diffAST, $formatMap, $deep) {
        $status = $diffAST[$key]['status'];
        $keyIn = $diffAST[$key]['key'];
        $tab = str_repeat("    ", $deep);
        $res = $tab;
        if ($status == 'nested') {
            $children = $diffAST[$key]['children'];
            return  $res . "    " . $keyIn . ": {\n"
                . mainFormatting($children, $deep + 1)
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
        return $res;
    }, array_keys($diffAST));

    return implode("", $formatted);
}
function formatArray($array, $deep)
{
    $stripedStr = strip(json_encode($array, JSON_PRETTY_PRINT), "\"", ",");
    $trimed = trim($stripedStr, "{} ");
    $tab = str_repeat("    ", $deep + 1);
    $res = str_replace("\n    ", "\n" . $tab . "    ", $trimed);

    return $res;
}

<?php

namespace Differ\Formatters\PrettyFormatter;

use function Funct\Strings\strip;

function toPrettyFormat($diffTree)
{
    return "{\n" . toPretty($diffTree, $deep = 0) . "}";
}

function toPretty($diffTree, $deep)
{
    $formatMap = ['added' => '+ ','deleted' => '- ','unchanged' => '  '];

    $formatted =  array_map(function ($key) use ($diffTree, $formatMap, $deep) {
        $status = $diffTree[$key]['status'];
        $keyIn = $diffTree[$key]['key'];
        $tab = str_repeat("    ", $deep);
        $res = $tab;
        if ($status == 'nested') {
            $children = $diffTree[$key]['children'];
            return  $res . "    " . $keyIn . ": {\n"
                . toPretty($children, $deep + 1)
                . str_repeat("    ", $deep + 1) . "}\n";
        }
        $res .= "  ";
        if ($status == 'changed') {
            $oldValue = (is_array($diffTree[$key]['oldValue'])) ? "{"
                . formatArray($diffTree[$key]['oldValue'], $deep)
                . str_repeat("    ", $deep + 1)
                . "}"
                : $diffTree[$key]['oldValue'];
            $oldValue = getFormattedValue($oldValue);

            $res = $res . '- ' . $keyIn . ': ' . $oldValue . "\n";

            // в случае changed нужно добавить еще "  " для смещения newValue;
            $res .= $tab . "  ";

            $newValue = (is_array($diffTree[$key]['newValue'])) ? "{"
                . formatArray($diffTree[$key]['newValue'], $deep)
                . str_repeat("    ", $deep + 1)
                . "}"
                : $diffTree[$key]['newValue'];
            $newValue = getFormattedValue($newValue);

            $res = $res . '+ ' . $keyIn . ': ' . $newValue . "\n";
        } else {
            $value = is_array($diffTree[$key]['value']) ? "{"
                . formatArray($diffTree[$key]['value'], $deep)
                . str_repeat("    ", $deep + 1)
                . "}"
                : $diffTree[$key]['value'];
            $value = getFormattedValue($value);

            $res = $res . $formatMap[$status] . $keyIn . ': ' . $value . "\n";
        }
        return $res;
    }, array_keys($diffTree));

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

function getFormattedValue($value)
{
    if (is_bool($value)) {
        return ($value) ? 'true' : 'false';
    }
    return $value;
}

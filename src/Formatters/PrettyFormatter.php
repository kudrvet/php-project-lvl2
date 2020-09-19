<?php

namespace Differ\Formatters\PrettyFormatter;

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
        if ($status == 'nested') {
            $children = $diffTree[$key]['children'];
            return  $tab . "    " . $keyIn . ": {\n"
                . toPretty($children, $deep + 1)
                . str_repeat("    ", $deep + 1) . "}\n";
        }
        if ($status == 'changed') {
            $oldValueFormatted = getFormattedValue($diffTree[$key]['oldValue'], $deep);
            $oldValueResult = $tab . "  " . '- ' . $keyIn . ': ' . $oldValueFormatted . "\n";

            $newValueFormatted = getFormattedValue($diffTree[$key]['newValue'], $deep);
            $newValueResult = $tab . "  " . '+ ' . $keyIn . ': ' . $newValueFormatted . "\n";

            return $oldValueResult . $newValueResult;
        }

        $value = getFormattedValue($diffTree[$key]['value'], $deep);

        return $tab . "  " . $formatMap[$status] . $keyIn . ': ' . $value . "\n";
    }, array_keys($diffTree));

    return implode("", $formatted);
}

function formatArray($array, $deep)
{
    $formatted = array_map(function ($key) use ($array, $deep) {

        if (!is_array($array[$key])) {
            return str_repeat("    ", $deep + 2)
            . $key . ": " . $array[$key] . "\n";
        }
        return str_repeat("    ", $deep + 2)
            . $key . ": {\n"
            . formatArray($array[$key], $deep + 1)
            . str_repeat("    ", $deep + 2)
            . "}\n";
    }, array_keys($array));

    return  implode("", $formatted);
}

function getFormattedValue($value, $deep)
{
    if (is_bool($value)) {
        return ($value) ? 'true' : 'false';
    }
    if (is_array($value)) {
        return "{\n"
            . formatArray($value, $deep)
            . str_repeat("    ", $deep + 1)
            . "}";
    }
    return $value;
}

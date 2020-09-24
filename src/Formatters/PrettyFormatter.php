<?php

namespace Differ\Formatters\PrettyFormatter;

function toPrettyFormat($diffTree, $deep = 0)
{
    $formatMap = ['added' => '+ ','deleted' => '- ','unchanged' => '  '];
    $tab = str_repeat("    ", $deep);
    $formattedData =  array_map(function ($outerKey) use ($diffTree, $formatMap, $deep, $tab) {
        $status = $diffTree[$outerKey]['status'];
        $key = $diffTree[$outerKey]['key'];

        switch ($status) {
            case 'nested':
                $children = $diffTree[$outerKey]['children'];
                $formattedValue = toPrettyFormat($children, $deep + 1);
                return "{$tab}    {$key}: {$formattedValue}";

            case 'changed':
                $oldValueFormatted = getFormattedValue($diffTree[$outerKey]['oldValue'], $deep);
                $oldValueResult = "{$tab}  - {$key}: {$oldValueFormatted}\n";

                $newValueFormatted = getFormattedValue($diffTree[$outerKey]['newValue'], $deep);
                $newValueResult = "{$tab}  + {$key}: {$newValueFormatted}";

                return $oldValueResult . $newValueResult;

            case 'added' || 'deleted' || 'unchanged':
                $value = getFormattedValue($diffTree[$outerKey]['value'], $deep);

                return "{$tab}  {$formatMap[$status]}{$key}: {$value}";
            default:
                throw new \Exception("Status {$status} should not existed in /$/diffTree[/$/outerKey]['status']");
        }
    }, array_keys($diffTree));

    $formattedResult = implode("\n", $formattedData);
    return "{\n{$formattedResult}\n{$tab}}";
}

function getFormattedValue($value, $deep)
{
    if (is_bool($value)) {
        return ($value) ? 'true' : 'false';
    }
    if (is_array($value)) {
        $tab = str_repeat("    ", $deep + 1);
        $formatted = array_map(function ($key) use ($value, $deep, $tab) {
            $formattedValue = is_array($value[$key]) ? getFormattedValue($value[$key], $deep + 1) : $value[$key];

            return "{$tab}    {$key}: {$formattedValue}";
        }, array_keys($value));

        $temp = implode("\n", $formatted);

        return "{\n{$temp}\n{$tab}}";
    }
    return $value;
}

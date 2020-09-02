<?php

namespace Differ\Differ;

use function Differ\Formatters\JsonFormatter\toJsonFormat;
use function Differ\Formatters\PrettyFormatter\toPrettyFormat;
use function Differ\Formatters\PlainFormatter\toPlainFormat;
use function Differ\Parsers\YamlParser\toAsoc;
use function Differ\Parsers\JsonParser\toAsoc as jsonToAsoc;

function buildDiffTree($conten1,$content2,$contentFormat)
{
    switch ($contentFormat) {
        case "yaml":
            $beforeData = toAsoc($conten1);
            $afterData = toAsoc($content2);
            break;
        case "json":
            $beforeData = jsonToAsoc($conten1);
            $afterData = jsonToAsoc($content2);
            break;
        default:
            throw new \Exception("Format {$contentFormat} is not supported! ");
    }

    return getDiffTree($beforeData, $afterData);
}

function buildFormattedDiff($diffTree,$format)
{
    switch ($format) {
        case 'pretty':
            return toPrettyFormat($diffTree);
        case 'plain':
            return toPlainFormat($diffTree);
        case 'json':
            return toJsonFormat($diffTree);
        default:
            throw new \Exception("Output format {$format} is not supported!");
    }
}

function genDiff($path1, $path2, $format = 'pretty')
{
    $contentBefore= file_get_contents($path1, true);
    $contentAfter = file_get_contents($path2, true);

    $fileBeforeFormat = pathinfo($path1, PATHINFO_EXTENSION);
    $fileAfterFormat= pathinfo($path2, PATHINFO_EXTENSION);

    if($fileBeforeFormat !== $fileAfterFormat) {
        throw new \Exception("Format of file's {$path1} and {$path2} are different!");
    }

   $diffTree = buildDiffTree($contentBefore,$contentAfter,$fileBeforeFormat);

    return buildFormattedDiff($diffTree,$format);
}

function getDiffTree(array $beforeData, array $afterData)
{
    $beforeData = array_map(function ($item) {
        return boolToString($item);
    }, $beforeData);

    $afterData = array_map(function ($item) {
        return boolToString($item);
    }, $afterData);


    $beforeKeys = array_keys($beforeData);
    $afterKeys = array_keys($afterData);
    $unionKeys = array_unique(array_merge($beforeKeys, $afterKeys));
    sort($unionKeys);

    return array_map(function ($key) use ($afterData, $beforeData) {

        $beforeValue = $beforeData[$key] ?? null;
        $afterValue = $afterData[$key] ?? null;

        if(is_null($beforeValue)) {
            return  ['key' => $key, 'value' => $afterValue, 'status' => 'added'];
        }

        if(is_null($afterValue)) {
            return  ['key' => $key, 'value' => $beforeValue, 'status' => 'deleted'];
        }

        if ($beforeValue == $afterValue) {
            return ['key' => $key, 'value' => $beforeValue, 'status' => 'unchanged'];
        }

        if (is_array($beforeValue) && is_array($afterValue)) {
            return ['key' => $key, 'status' => 'nested',
                'children' => getDiffTree($beforeValue, $afterValue)];
        } else {
            return ['key' => $key, 'oldValue' => $beforeValue,
                'newValue' => $afterValue, 'status' => 'changed'];
        }

    }, $unionKeys);

}
function boolToString($value)
{
    if (is_bool($value)) {
        return ($value) ? 'true' : 'false';
    }
    return $value;
}

<?php

namespace Differ\Differ;

use function Differ\Formatters\JsonFormatter\toJsonFormat;
use function Differ\Formatters\PrettyFormatter\toPrettyFormat;
use function Differ\Formatters\PlainFormatter\toPlainFormat;
use function Differ\Parsers\parse;

function genDiff($path1, $path2, $format = 'pretty')
{
    $contentBefore = file_get_contents($path1, true);
    $contentAfter = file_get_contents($path2, true);

    $fileBeforeFormat = pathinfo($path1, PATHINFO_EXTENSION);
    $fileAfterFormat = pathinfo($path2, PATHINFO_EXTENSION);

    $beforeData = parse($contentBefore, $fileBeforeFormat);
    $afterData = parse($contentAfter, $fileAfterFormat);

    $diffTree = getDiffTree($beforeData, $afterData);

    return buildFormattedDiff($diffTree, $format);
}

function getDiffTree(array $beforeData, array $afterData)
{
    $beforeKeys = array_keys($beforeData);
    $afterKeys = array_keys($afterData);
    $unionKeys = array_unique(array_merge($beforeKeys, $afterKeys));
    sort($unionKeys);

    return array_map(function ($key) use ($afterData, $beforeData) {

        if (!array_key_exists($key, $beforeData)) {
            return ['key' => $key, 'status' => 'added', 'value' => $afterData[$key]];
        }

        if (!array_key_exists($key, $afterData)) {
            return ['key' => $key, 'status' => 'deleted', 'value' => $beforeData[$key]];
        }

        if ($beforeData[$key] === $afterData[$key]) {
            return ['key' => $key, 'status' => 'unchanged', 'value' => $beforeData[$key]];
        }

        if (is_array($beforeData[$key]) && is_array($afterData[$key])) {
            return ['key' => $key, 'status' => 'nested',
                'children' => getDiffTree($beforeData[$key], $afterData[$key])];
        } else {
            return ['key' => $key, 'status' => 'changed', 'oldValue' => $beforeData[$key],
                'newValue' => $afterData[$key],];
        }
    }, $unionKeys);
}

function buildFormattedDiff($diffTree, $format)
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

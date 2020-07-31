<?php

namespace Differ\Differ;

use function Differ\Parsers\YamlParser\toAsoc;
use function  Differ\Parsers\JsonParser\toAsoc as jsonToAsoc;

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
            $afterAsoc =  jsonToAsoc($fileAfter);
            break;
    }

    $result = "";
    foreach ($afterAsoc as $key => $value) {
        if (is_bool($value)) {
            $value = ($value) ? 'true' : 'false';
        }

        if (isset($beforeAsoc[$key])) {
            // not changed
            if ($beforeAsoc[$key] == $value) {
                $result = $result . "{$key}: {$value}\n";
                // changed
            } elseif ($beforeAsoc[$key] !== $value) {
                $result .= "+ {$key}: {$value}\n";
                $result .= "- {$key}: {$beforeAsoc[$key]}\n";
            }
            //add
        } else {
            $result .= "+ {$key}: {$value}\n";
        }
    }
        //deleted
    $deleted = array_diff_key($beforeAsoc, $afterAsoc);
    foreach ($deleted as $key => $value) {
        $result .= "- {$key}: {$value}\n";
    }
        return "{\n$result}" ;
}

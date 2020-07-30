<?php

namespace Differ\Differ;

use function PhpTrees\Trees\getChildren;
use function PhpTrees\Trees\isDirectory;

function genDiff($path1, $path2)
{
    $file1Content = file_get_contents($path1, true);
    $file2Content = file_get_contents($path2, true);
    $beforeAsoc = json_decode($file1Content, true);
    $afterAsoc = json_decode($file2Content, true);
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

function add($first, $second)
{
    return $first + $second;
}

//print_r(genDiff('./before.json','/Users/vitaliy/testFolder/php-project-lvl2/bin/after.json'));

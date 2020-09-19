<?php

namespace Differ\Differ\Tests;

$autoloadPath1 = __DIR__ . '/../../../autoload.php';
$autoloadPath2 = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} else {
    require_once $autoloadPath2;
}

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */

    public function testGenDiff($pathToExpected,$pathToFileBefore,$pathToFileAfter,$format)
    {
        $expected = file_get_contents($pathToExpected);
        $actual = genDiff($pathToFileBefore, $pathToFileAfter,$format);
        $this->assertEquals($expected, $actual);
    }
    public function additionProvider()
    {
        $pathsToExpected = [
            './tests/fixtures/recursiveInputWithPrettyFormatterResult',
            './tests/fixtures/recursiveInputWithPrettyFormatterResult',
            './tests/fixtures/recursiveInputWithPlainFormatterResult',
            './tests/fixtures/recursiveInputWithPlainFormatterResult',
            './tests/fixtures/recursiveInputWithJsonFormatterResult.json',
            './tests/fixtures/recursiveInputWithJsonFormatterResult.json'
        ];

        $pathsToFileBefore= [
            './tests/fixtures/recursiveJsonBefore.json',
            './tests/fixtures/recursiveYmlBefore.yaml',
            './tests/fixtures/recursiveJsonBefore.json',
            './tests/fixtures/recursiveYmlBefore.yaml',
            './tests/fixtures/recursiveJsonBefore.json',
            './tests/fixtures/recursiveYmlBefore.yaml'
        ];

        $pathsToFileAfter = [
            './tests/fixtures/recursiveJsonAfter.json',
            './tests/fixtures/recursiveYmlAfter.yaml',
            './tests/fixtures/recursiveJsonAfter.json',
            './tests/fixtures/recursiveYmlAfter.yaml',
            './tests/fixtures/recursiveJsonAfter.json',
            './tests/fixtures/recursiveYmlAfter.yaml'
        ];

        $formats = [
            'pretty',
            'pretty',
            'plain',
            'plain',
            'json',
            'json'
        ];

        $res =[];
        foreach ($pathsToExpected as $key => $expected) {
            $res[] = [$expected, $pathsToFileBefore[$key], $pathsToFileAfter[$key], $formats[$key]];
        }

        return $res;
    }
}






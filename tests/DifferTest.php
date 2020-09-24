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

    public function testGenDiff($pathToFileBefore, $pathToFileAfter,$formatterWithPathToResult)

    {
        $formatter = $formatterWithPathToResult['formatter'];
        $pathToResult = $formatterWithPathToResult['path'];

        $actual = genDiff($pathToFileBefore, $pathToFileAfter,$formatter);
        $expected = file_get_contents($pathToResult);

        $this->assertEquals($expected, $actual);

    }

    public function additionProvider()
    {

        $pathsToFileBefore = [
            './tests/fixtures/before.json',
            './tests/fixtures/before.yaml'
        ];

        $pathsToFileAfter = [
            './tests/fixtures/after.json',
            './tests/fixtures/after.yaml',
        ];

        $formattersWithPathsToResult = [
            ['formatter'=>'pretty', 'path' =>'./tests/fixtures/prettyFormatterResult'],
            ['formatter' => 'plain','path' => './tests/fixtures/plainFormatterResult'],
            ['formatter' => 'json', 'path' => './tests/fixtures/jsonFormatterResult.json']
        ];

        $res = [];
        foreach ($pathsToFileBefore as $key => $pathToFileBefore) {
            foreach ($formattersWithPathsToResult as $data) {
                $res[] = [$pathToFileBefore, $pathsToFileAfter[$key], $data];
            }
        }
        return $res;

    }
}







